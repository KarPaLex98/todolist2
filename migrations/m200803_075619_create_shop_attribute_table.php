<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_attribute}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200803_075619_create_shop_attribute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_attribute}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(),
            'sort' => $this->integer()->notNull(),
            'alias' => $this->string(),
            'title' => $this->string(),
        ]);

        // creates index for column `type_id`
        $this->createIndex(
            '{{%idx-shop_attribute-type_id}}',
            '{{%shop_attribute}}',
            'type_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `type_id`
        $this->dropIndex(
            '{{%idx-shop_attribute-type_id}}',
            '{{%shop_attribute}}'
        );

        $this->dropTable('{{%shop_attribute}}');
    }
}
