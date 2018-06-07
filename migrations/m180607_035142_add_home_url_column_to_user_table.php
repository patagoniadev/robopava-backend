<?php

use yii\db\Migration;

/**
 * Handles adding home_url to table `user`.
 */
class m180607_035142_add_home_url_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'home_url', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'home_url');
    }
}
