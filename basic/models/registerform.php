<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repeatPassword;
    public $facebook;
    public $twitter;
   

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password','repeatPassword','email'], 'required'],
            //[['facebook', 'twitter'], 'integer'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword()
    {
        if ($this->password!=$this->repeatPassword){
            $this->addError('repeatPassword', 'Passwords does not match');
        }
    }
    
    public function validateUsername()
    {
        $user = User::findByUsername($this->username);
        if($user!='')
        {
            $this->addError('username', 'Username already exist');
        }
    }
    
    public function validateEmail()
    {
        $user = User::find()->where(['email'=> $this->email])->one();
        if($user!='')
        {
            $this->addError('email', 'Email already exist');
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {   
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
