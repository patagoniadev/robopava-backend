<?php

namespace app\api\modules\v1\models;

use Yii;
use yii\web\Link;
use yii\helpers\Url;
use yii\web\Linkable;

class User extends \app\models\User implements Linkable
{
    public static function tableName()
    {
        return 'user';
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'status' => function ($model) {
                return $this->getStatusText($model->status);
            },
            'created_at',
            'updated_at',
        ];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
            'index' => Url::to(['user/index'], true),
        ];
    }

    public function getStatusText($status)
    {
        $statusArray = [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive'
        ];

        return $statusArray[$status];
    }
}