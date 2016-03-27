<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\RegisterForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
              ],
        ];
    }
    
    public function actionIndex()
    {
        $this->layout = 'main-fluid';
        //$roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        $user = Yii::$app->user->identity->_id;
        //$can = Yii::$app->user->can("admin");
        //print_r("Can:"+$can);
        //print_r($user);
        //print_r($roles);
        //die();
        if ($register = Yii::$app->session->getFlash("register")) {
            Yii::$app->session->setFlash("register", true, true);
            $this->redirect(\Yii::$app->urlManager->createUrl("site/registersocial"));
        }
        /*$model = \app\models\Book::find()->all();
        print_r($model[0]->Name);
        $user = \app\models\User::find()->all();
        print_r($user);*/
        //die();
        $pass = Yii::$app->getSecurity()->validatePassword('P@ssw0rd', '$2y$13$xA83JGFFNJvmlAJm2bgmUeQRYNheLSpeHeTT8QU8nR6tS0RQWGHrq');
        //print_r($pass); die();
        return $this->render('index');
    }

    public function actionLogin()
    {

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        else
        {
            //print_r("Guest");
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $collection = Yii::$app->mongodb->getCollection('User');
                $collection->insert(['name' => 'John Smith', 'status' => 1]);
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        
        return $this->render('about');
    }
    
    public function oAuthSuccess($client) {
        // get user data from client
        $userAttributes = $client->getUserAttributes();
        // do some thing with user data. for example with $userAttributes['email']
        //print_r($client->id);
        if($client->id=='facebook')
        {
            $user= \app\models\User::getUserByFacebook($userAttributes['id']);
            if($user!='')
            {
                Yii::$app->user->login($user);
            }
            else
            {
                print_r($userAttributes);
                print_r("Time to create user");
                Yii::$app->session->setFlash("register", true, true);
                Yii::$app->session->setFlash("facebook", $userAttributes['id'], true);
                
            }
        }
        else if($client->id=='twitter')
        {
            $user= \app\models\User::getUserByTwitter($userAttributes['id_str']);
            if($user!='')
            {
                Yii::$app->user->login($user);
            }
            else
            {
                Yii::$app->session->setFlash("register", true, true);
                Yii::$app->session->setFlash("twitter", $userAttributes['id_str'], true);
            }
        }
        //die();
      }
      
    public function actionRegistersocial()
    {
        $register = Yii::$app->session->getFlash("register");
        Yii::$app->session->setFlash("register", $register, true);
        
        if ($register) {
            //print_r("Time to register1");
            $model = new \app\models\RegisterForm();
            $facebook = $register = Yii::$app->session->getFlash("facebook");
            $twitter = $register = Yii::$app->session->getFlash("twitter");
            if ($facebook!=false && $facebook!='') {
                Yii::$app->session->setFlash("facebook", $facebook, true);
                $model->facebook = $facebook;
            }
            else if ($twitter!=false && $twitter!='') {
                Yii::$app->session->setFlash("twitter", $twitter, true);
                $model->twitter = $twitter;
            }
            
            
            if ($model->load(Yii::$app->request->post()) && $model->validate(null,false)) {
                
                
                
                $register = Yii::$app->session->getFlash("register");
                
                $collection = Yii::$app->mongodb->getCollection('User');
                $collection->insert(['username' => $model->username,
                                    'status' =>\app\models\User::STATUS_ACTIVE,
                                    'password' =>$model->password,
                                    'hash' =>Yii::$app->getSecurity()->generatePasswordHash($model->password),
                                    'role' =>\app\models\User::ROLE_USER,
                                    'facebook' =>$model->facebook,
                                    'twitter' =>$model->twitter,
                                    'token'=>\Yii::$app->security->generateRandomString(32)
                        ]);
                
                // does not work $user->save();
                $user = \app\models\User::getUserByUsername($model->username);
                Yii::$app->user->login($user);
                
                $this->redirect(\Yii::$app->urlManager->createUrl("site/index"));
            }
            return $this->render('registersocial', [
                'model' => $model,
            ]);
        }
        else
        {
            $this->redirect(\Yii::$app->urlManager->createUrl("site/index"));
        }
    }
}
