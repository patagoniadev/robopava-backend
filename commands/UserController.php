<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;
use yii\base\UserException;

class UserController extends Controller {
    public function actionCreate($username, $pass, $email=null) {
        if($user = User::findByUsername($username)) {
            echo "El usuario $username ya existe\n";
            return;
        }
        $user = new User();
        $user->username = $username;
        $user->clearpw = $pass;
        $user->email = $email;
        if($email === null) {
            $user->email = \Yii::$app->params['adminEmail'];
        }
        $user->generateAuthKey();
        $user->save();
    }

    public function actionCambiarClave($username, $new_pass) {
        $user = User::findByUsername($username);
        $user->setPassword($new_pass);
        $user->save();
    }
}