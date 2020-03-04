<?php

namespace Backend\Library\Phalcon\Form\Decorator;

use Phalcon\Forms\Form;

/**
 * Class PrefixForm
 *
 * @author Artem Pasvlovskiy tema23p@gmail.com
 *
 */
class PrefixForm extends \Phalcon\Di\Injectable implements \Countable, \Iterator
{

    private $prefix = '';

    private $hasModifiElement = [];

    /**
     * @var Form
     */
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param string $prefix
     *
     * @return PrefixForm
     */
    public function setPrefix(string $prefix): PrefixForm
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Returns an element added to the form by its name
     *
     * @param string $name
     *
     * @return \Phalcon\Forms\ElementInterface
     */
    public function get($name)
    {
        $element = $this->form->get($name);

        if(!empty($this->prefix) && !isset($this->hasModifiElement[$name])){
            $element->setAttribute('name',$this->prefix.'['.$name.']');
            $this->hasModifiElement[$name] = 1;
        }
        return $element;
    }

    /**
     * @param mixed $validation
     */
    public function setValidation($validation)
    {
        return $this->form->setValidation($validation);
    }

    public function getValidation()
    {
        return $this->form->getValidation();
    }

    /**
     * Sets the form's action
     *
     * @param string $action
     *
     * @return Form
     */
    public function setAction($action)
    {
        return $this->form->setAction($action);
    }

    /**
     * Returns the form's action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->form->getAction();
    }

    /**
     * Sets an option for the form
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return Form
     */
    public function setUserOption($option, $value)
    {
        return $this->form->setUserOption($option, $value);
    }

    /**
     * Returns the value of an option if present
     *
     * @param string $option
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getUserOption($option, $defaultValue = null)
    {
        return $this->form->setUserOption($option, $defaultValue);
    }

    /**
     * Sets options for the element
     *
     * @param array $options
     *
     * @return Form
     */
    public function setUserOptions(array $options)
    {
        return $this->form->setUserOptions($options);
    }

    /**
     * Returns the options for the element
     *
     * @return array
     */
    public function getUserOptions()
    {
        return $this->form->getUserOptions();
    }

    /**
     * Sets the entity related to the model
     *
     * @param object $entity
     *
     * @return Form
     */
    public function setEntity($entity)
    {
        return $this->form->setEntity($entity);
    }

    /**
     * Returns the entity related to the model
     *
     * @return object
     */
    public function getEntity()
    {
        return $this->form->getEntity();
    }

    /**
     * Returns the form elements added to the form
     *
     * @return \Phalcon\Forms\ElementInterface[]
     */
    public function getElements()
    {
        return $this->form->getElements();
    }

    /**
     * Binds data to the entity
     *
     * @param array  $data
     * @param object $entity
     * @param array  $whitelist
     *
     * @return Form
     */
    public function bind(array $data, $entity, $whitelist = null)
    {
        return $this->form->bind($data, $entity, $whitelist);
    }

    /**
     * Validates the form
     *
     * @param array  $data
     * @param object $entity
     *
     * @return bool
     */
    public function isValid($data = null, $entity = null)
    {
        return $this->form->isValid($data, $entity);
    }

    /**
     * Returns the messages generated in the validation.
     *
     * <code>
     * if ($form->isValid($_POST) == false) {
     *     // Get messages separated by the item name
     *     // $messages is an array of Group object
     *     $messages = $form->getMessages(true);
     *
     *     foreach ($messages as $message) {
     *         echo $message, "<br>";
     *     }
     *
     *     // Default behavior.
     *     // $messages is a Group object
     *     $messages = $form->getMessages();
     *
     *     foreach ($messages as $message) {
     *         echo $message, "<br>";
     *     }
     * }
     * </code>
     *
     * @param bool $byItemName
     *
     * @return array|\Phalcon\Validation\Message\Group
     */
    public function getMessages($byItemName = false)
    {
        return $this->form->getMessages($byItemName);
    }

    /**
     * Returns the messages generated for a specific element
     *
     * @param string $name
     *
     * @return \Phalcon\Validation\Message\Group
     */
    public function getMessagesFor($name)
    {
        return $this->form->getMessagesFor($name);
    }

    /**
     * Check if messages were generated for a specific element
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasMessagesFor($name)
    {
        return $this->form->hasMessagesFor($name);
    }

    /**
     * Adds an element to the form
     *
     * @param \Phalcon\Forms\ElementInterface $element
     * @param string                          $position
     * @param bool                            $type
     *
     * @return Form
     */
    public function add(\Phalcon\Forms\ElementInterface $element, $position = null, $type = null)
    {
        return $this->form->add($element, $position, $type);
    }

    /**
     * Renders a specific item in the form
     *
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function render($name, $attributes = null)
    {
        return $this->form->add($name, $attributes);
    }


    /**
     * Generate the label of an element added to the form including HTML
     *
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function label($name, array $attributes = null)
    {
        return $this->form->label($name,$attributes);
    }

    /**
     * Returns a label for an element
     *
     * @param string $name
     *
     * @return string
     */
    public function getLabel($name)
    {
        return $this->form->getLabel($name);
    }

    /**
     * Gets a value from the internal related entity or from the default value
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function getValue($name)
    {
        return $this->form->getValue($name);
    }

    /**
     * Check if the form contains an element
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->form->has($name);
    }

    /**
     * Removes an element from the form
     *
     * @param string $name
     *
     * @return bool
     */
    public function remove($name)
    {
        return $this->form->remove($name);
    }

    /**
     * Clears every element in the form to its default value
     *
     * @param array $fields
     *
     * @return Form
     */
    public function clear($fields = null)
    {
        return $this->form->clear($fields);
    }

    /**
     * Returns the number of elements in the form
     *
     * @return int
     */
    public function count()
    {
        return $this->form->count();
    }

    /**
     * Rewinds the internal iterator
     */
    public function rewind()
    {
        return $this->form->rewind();
    }

    /**
     * Returns the current element in the iterator
     *
     * @return bool|\Phalcon\Forms\ElementInterface
     */
    public function current()
    {
        return $this->form->current();
    }

    /**
     * Returns the current position/key in the iterator
     *
     * @return int
     */
    public function key()
    {
        return $this->form->key();
    }

    /**
     * Moves the internal iteration pointer to the next position
     */
    public function next()
    {
        return $this->form->next();
    }

    /**
     * Check if the current element in the iterator is valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->form->valid();
    }

}