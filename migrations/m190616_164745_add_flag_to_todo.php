<?php

use yii\db\Migration;

/**
 * Class m190616_164745_add_flag_to_todo
 */
class m190616_164745_add_flag_to_todo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%todo}}', 'is_Send', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->DropColumn('{{%todo}}', 'is_Send');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190616_164745_add_flag_to_todo cannot be reverted.\n";

        return false;
    }
    */
}
