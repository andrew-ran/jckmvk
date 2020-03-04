package socket

import (
	"bytes"
	"encoding/json"
	"github.com/gorilla/websocket"
	"log"
	"time"
)

const (
	// Time allowed to write a message to the peer.
	writeWait = 10 * time.Second
	// Time allowed to read the next pong message from the peer.
	pongWait = 60 * time.Second
	// Send pings to peer with this period. Must be less than pongWait.
	pingPeriod = (pongWait * 9) / 10
	// Maximum message size allowed from peer.
	maxMessageSize = 512
)

var (
	newline = []byte{'\n'}
	space   = []byte{' '}
)

var Upgrader = websocket.Upgrader{
	ReadBufferSize:  1024,
	WriteBufferSize: 1024,
}

// Client is a middleman between the websocket connection and the Hub.
type Client struct {
	Hub *Hub
	// The websocket connection.
	Conn *websocket.Conn
	// Buffered channel of outbound wsmessages.
	Send                    chan WSMessage
	SessionId               string
	UserId                  int64
	SubscribeMessageTypeMap map[string]bool
}

type WSMessage struct {
	Message   string `json:"message"`
	Type      string `json:"type"`
	SessionId string `json:"sessionId"`
	UserId    int64  `json:"userId"`
}

// readPump pumps wsmessages from the websocket connection to the Hub.
//
// The application runs readPump in a per-connection goroutine. The application
// ensures that there is at most one reader on a connection by executing all
// reads from this goroutine.
func (c *Client) ReadPump() {
	defer func() {
		c.Hub.Unregister <- c
		c.Conn.Close()
	}()
	c.Conn.SetReadLimit(maxMessageSize)
	c.Conn.SetReadDeadline(time.Now().Add(pongWait))
	c.Conn.SetPongHandler(func(string) error { c.Conn.SetReadDeadline(time.Now().Add(pongWait)); return nil })
	for {
		_, message, err := c.Conn.ReadMessage()
		if err != nil {
			if websocket.IsUnexpectedCloseError(err, websocket.CloseGoingAway, websocket.CloseAbnormalClosure) {
				log.Printf("error: %v", err)
			}
			break
		}
		message = bytes.TrimSpace(bytes.Replace(message, newline, space, -1))

		subscribeMessage := &struct {
			Type   string   `json:"type"`
			Events []string `json:"events"`
		}{}
		err = json.Unmarshal(message, subscribeMessage)
		if err == nil && subscribeMessage.Type == INNER_TYPE_USER_SUBSCRYBE {
			if subscribeMessage.Events == nil || len(subscribeMessage.Events) == 0 {
				c.SubscribeMessageTypeMap = map[string]bool{}
			} else {
				for _, value := range subscribeMessage.Events {
					c.SubscribeMessageTypeMap[value] = true
				}
			}
		} else {
			c.Hub.Broadcast <- WSMessage{
				Message:   string(message),
				SessionId: c.SessionId,
				UserId:    c.UserId,
				Type:      TYPE_USER_MESSAGE,
			}
		}
	}
}

// writePump pumps wsmessages from the Hub to the websocket connection.
//
// A goroutine running writePump is started for each connection. The
// application ensures that there is at most one writer to a connection by
// executing all writes from this goroutine.
func (c *Client) WritePump() {
	ticker := time.NewTicker(pingPeriod)
	defer func() {
		ticker.Stop()
		c.Conn.Close()
	}()
	for {
		select {
		case message, ok := <-c.Send:
			c.Conn.SetWriteDeadline(time.Now().Add(writeWait))
			if !ok {
				// The Hub closed the channel.
				c.Conn.WriteMessage(websocket.CloseMessage, []byte{})
				return
			}
			w, err := c.Conn.NextWriter(websocket.TextMessage)
			if err != nil {
				return
			}
			w.Write([]byte(message.Message))
			if err := w.Close(); err != nil {
				return
			}
		case <-ticker.C:
			c.Conn.SetWriteDeadline(time.Now().Add(writeWait))
			if err := c.Conn.WriteMessage(websocket.PingMessage, nil); err != nil {
				return
			}
		}
	}
}
