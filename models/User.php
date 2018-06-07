<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use mdm\admin\components\Configs;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $home_url
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    public $clearpw;    // cleartext password
    public $clearpwr;   // cleartext password repeat
    public $clearpass;  // cleartext password actual para verificar en el cambio de clave.

    /* @var array contiene las posibles páginas de inicio para el usuario */
    public static $paginasInicio = [
        '/site/index' => 'Index',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Configs::instance()->userTable;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['clearpw', 'clearpwr'], 'safe', 'on' => ['update']],
            [['home_url'], 'string', 'max' => 250],
            [['clearpw', 'clearpwr'], 'string', 'max' => 50, 'on' => ['insert']],
            [['clearpass', 'clearpw', 'clearpwr'], 'required', 'on' => ['changepass']],
            ['clearpw', 'compare', 'compareAttribute' => 'clearpwr', 'operator' => '===', 'type' => 'string', 'message' => 'Debe repetir la clave correctamente.', 'on' => ['insert', 'changepass', 'update']],
            ['username', 'unique', 'message'=> 'Dicho nombre ya se encuentra utilizado.'],
        ];
    }

    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)) {
            return false;
        }
        
        if ($this->isNewRecord) {
            $this->auth_key = \Yii::$app->security->generateRandomString();
            $this->setPassword($this->clearpw);
        } else {
            if($this->scenario === 'changepass') {
                if($this->validatePassword($this->clearpass))
                    $this->setPassword($this->clearpw);
                else
                    $this->addError('clearpass', 'Clave actual no valida');
            } elseif ($this->scenario === 'update' && !empty($this->clearpw)) {
                $this->setPassword($this->clearpw);
            }
        }

        return !$this->hasErrors();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Deactivates an user
     *
     * @return boolean is user is deactivated
     */
    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        return $this->save();
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getDb()
    {
        return Configs::userDb();
    }

    public function getPaginaInicio()
    {
        $paginaInicio = ArrayHelper::getValue(static::$paginasInicio, $this->home_url, 'site/index');
        return Url::to($paginaInicio);
    }

}
