<?php

use yii\db\Migration;

/**
 * Class m190616_010308_add_time_date_to_todo
 */
class m190616_010308_add_time_date_to_todo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%todo}}', 'date_time', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%todo}}', 'date_time');

}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190616_010308_add_time_date_to_todo cannot be reverted.\n";

        return false;
    }
    */
}
