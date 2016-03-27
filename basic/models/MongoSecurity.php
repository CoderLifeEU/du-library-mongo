<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

class MongoSecurity 
{
    
    public function hasRole($role)
    {
        $user = Yii::$app->user->identity;
        if($user->role==$role) return true;
        else return false;
    }
    
    public function isAdminSafe($token=null)
    {
        if($token==null || !$token)
        {
            $token = Yii::$app->user->identity->token;
        }
        return self::isAdmin($token);
    }
    
    public function isAdmin($token)
    {
        $isAdmin = false;
        $admins = User::getAdmins();
        for($i=0;$i<count($admins);$i++)
        {
            //print_r($admins[$i]->token);
            if($admins[$i]->token==$token) return true;
        }
        return false;
    }
    
    public function getUserByTokenSafe($token=null)
    {
        if($token==null || !$token)
        {
            $token = Yii::$app->user->identity->token;
        }
        return self::isAdmin($token);
    }
    public function getUserByToken($token)
    {
        $user = User::getUserByToken($token);
        if($user->token==$token) return $user;
        else return false;
    }

}