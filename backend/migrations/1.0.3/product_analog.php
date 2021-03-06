<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ProductAnalogMigration_103
 */
class ProductAnalogMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('product_analog', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 20,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'product_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'analog_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'product_id'
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'analog_id'
                        ]
                    ),
                    new Column(
                        'product_attribute_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'analog_attribute_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'product_attribute_id'
                        ]
                    ),
                    new Column(
                        'bind_type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 3,
                            'after' => 'analog_attribute_id'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('FK_product_analog_product', ['product_id'], null),
                    new Index('FK_product_analog_product_2', ['analog_id'], null),
                    new Index('FK_product_analog_user', ['user_id'], null),
                    new Index('FK_product_analog_product_to_property', ['product_attribute_id'], null),
                    new Index('FK_product_analog_product_to_property_2', ['analog_attribute_id'], null)
                ],
                'references' => [
                    new Reference(
                        'FK_product_analog_product',
                        [
                            'referencedTable' => 'product',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['product_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_product_analog_product_2',
                        [
                            'referencedTable' => 'product',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['analog_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_product_analog_product_to_property',
                        [
                            'referencedTable' => 'attribute_product',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['product_attribute_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_product_analog_product_to_property_2',
                        [
                            'referencedTable' => 'attribute_product',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['analog_attribute_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_product_analog_user',
                        [
                            'referencedTable' => 'user',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['user_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
