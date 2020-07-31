<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m200731_095620_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'tree'       => $this->integer()->notNull(),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'depth'      => $this->integer()->notNull(),
            'position'   => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('category');
    }
}
