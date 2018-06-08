<?php
namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\api\modules\v1\models\User';
}