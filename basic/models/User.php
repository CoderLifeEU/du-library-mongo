<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    const ROLE_USER = "user";
    const ROLE_ADMIN = "admin";
    const ROLE_GUEST= "guest";
    
    
    public $id;
    public $username;
    public $password;
    public $hash;
    public $authKey;
    public $accessToken;
    public $facebook;
    public $twitter;
    public $status;
    public $role;
    public $token;
    
    public $created_at;
    public $updated_at;
    
    public static function collectionName() {
        return ['dudb','User'];
        //parent::collectionName();
    }
    
    public function attributes() {
        return [
          "_id"
          ,"Username"
          ,"Password"
          ,"Email"
          ,"Status"
          //,"auth_key"
        ];
        //parent::attributes();
    }
    
    public function behaviors() {
        return 
        [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                //ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                //ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
        ];
        //parent::behaviors();
    }
    
    public function rules() {
        return [
            ['status','default','value' => self::STATUS_ACTIVE],
            ['status','in','range' => [self::STATUS_ACTIVE,self::STATUS_DELETED]],
            ['role','default','value' => self::ROLE_USER],
            ['role','in','range' => [self::ROLE_USER,self::ROLE_ADMIN]]
        ];
        //parent::rules();
    }
   

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException("findIdentityByAccessToken is not implemented");
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //print_r($username); 
        //print_r(static::findOne(['Username' => $username]));
        //die();
        return static::findOne(['username' => $username]);
        /*
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
        //return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**t
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->hash);
        //return $this->password === $password;
    }
    
    public static function getUserByUsername($username)
    {
        return User::find()->where(['username' => $username])->one();
    }
    
    public static function getUserByFacebook($facebook)
    {
        return User::find()->where(['facebook' => $facebook])->one();
    }
    
    public function getUserByTwitter($twitter)
    {
        return User::find()->where(['twitter' => $twitter])->one();
    }
    
    public function getAdmins()
    {
        return User::find()->where(['role' => self::ROLE_ADMIN])->all();
    }
    
    public function getUserByToken($token)
    {
        return User::find()->where(['token' => $token])->one();
    }
    
}
