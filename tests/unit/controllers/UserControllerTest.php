<?php
namespace app\tests\unit\controllers;

use Yii;
use app\models\User;
use app\commands\UserController;


class UserControllerTest extends \Codeception\Test\Unit
{
    /**
     * @depends app\tests\unit\models\UserTest:testValidateUser
     */
    public function testActionCreate()
    {
        $userController = Yii::createObject([
            'class' => 'app\commands\UserController'
        ], [null, null]);

        $userController->actionCreate('prueba', 'prueba', 'prueba@hotmail.com');
        expect_that($user = User::findByUsername('prueba'));
        expect_that($user->validatePassword('prueba'));
    }
}