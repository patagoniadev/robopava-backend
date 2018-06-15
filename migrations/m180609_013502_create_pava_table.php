<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pava`.
 */
class m180609_013502_create_pava_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pava', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pava');
    }
}
