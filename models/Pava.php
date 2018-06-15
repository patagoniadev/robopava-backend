<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pava".
 *
 * @property int $id
 * @property string $ip
 */
class Pava extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pava';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'ip'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
        ];
    }
}
