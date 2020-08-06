<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_attribute_value}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product}}`
 * - `{{%shop_attribute}}`
 */
class m200803_080511_create_shop_attribute_value_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_attribute_value}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-shop_attribute_value-product_id}}',
            '{{%shop_attribute_value}}',
            'product_id'
        );

//        // add foreign key for table `{{%product}}`
//        $this->addForeignKey(
//            '{{%fk-shop_attribute_value-product_id}}',
//            '{{%shop_attribute_value}}',
//            'product_id',
//            '{{%product}}',
//            'id',
//            'CASCADE'
//        );

        // creates index for column `attribute_id`
        $this->createIndex(
            '{{%idx-shop_attribute_value-attribute_id}}',
            '{{%shop_attribute_value}}',
            'attribute_id'
        );

        // add foreign key for table `{{%shop_attribute}}`
        $this->addForeignKey(
            '{{%fk-shop_attribute_value-attribute_id}}',
            '{{%shop_attribute_value}}',
            'attribute_id',
            '{{%shop_attribute}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        // drops foreign key for table `{{%product}}`
//        $this->dropForeignKey(
//            '{{%fk-shop_attribute_value-product_id}}',
//            '{{%shop_attribute_value}}'
//        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-shop_attribute_value-product_id}}',
            '{{%shop_attribute_value}}'
        );

        // drops foreign key for table `{{%shop_attribute}}`
        $this->dropForeignKey(
            '{{%fk-shop_attribute_value-attribute_id}}',
            '{{%shop_attribute_value}}'
        );

        // drops index for column `attribute_id`
        $this->dropIndex(
            '{{%idx-shop_attribute_value-attribute_id}}',
            '{{%shop_attribute_value}}'
        );

        $this->dropTable('{{%shop_attribute_value}}');
    }
}
