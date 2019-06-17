<?php

use yii\db\Migration;

/**
 * Class m190614_211449_change_user_table
 */
class m190614_211449_change_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'vk_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'vk_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190614_211449_change_user_table cannot be reverted.\n";

        return false;
    }
    */
}
