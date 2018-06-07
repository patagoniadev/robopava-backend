<?php

namespace app\tests\unit\models;

use app\models\User;
use app\tests\unit\fixtures\UserFixture;

class UserTest extends \Codeception\Test\Unit
{
    public function _fixtures()
    {
        return [
            'users' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }
    public function testFindUserById()
    {
        $user = User::findIdentity(1);
        expect_that(isset($user));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        return true;
        /**
        expect_that($user = User::findIdentityByAccessToken('100-token'));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentityByAccessToken('non-existing'));
        */
    }

    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('admin'));
        expect_not(User::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = User::findByUsername('admin');
        expect_that($user->validateAuthKey('test100key'));
        expect_not($user->validateAuthKey('test102key'));

        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}
