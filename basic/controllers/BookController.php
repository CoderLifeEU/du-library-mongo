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

class BookController extends Controller
{
    
    public static $pageSize = 3;
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
        return $this->render('index');
    }
    
    public function actionMy()
    {
        return $this->render('my');
    }
    
    public function actionAdmin($token=null)
    {
        if(!\app\models\MongoSecurity::isAdminSafe($token)) return json_encode(array("status"=>"false","data"=>"Access denied"));
        return $this->render('admin');
    }

    public function actionGet($page,$filter= false)
    {
        
        $books = \app\models\Book::getBooks($page,3,$filter);
        //print_r($books);
        $json_books = [];
        for($i=0;$i<count($books);$i++)
        {
            $obj = [];
            $obj['id'] = $books[$i]->_id;
            $obj['name'] = $books[$i]->name;
            $obj['description'] = $books[$i]->description;
            $obj['pages'] = $books[$i]->pages;
            $obj['quantity'] = $books[$i]->quantity;
            $obj['image'] = $books[$i]->image;
            array_push($json_books, $obj);
        }
        //print_r($books[0]->_id);
        //die();
        return json_encode($json_books);
    }
    
    public function actionMybooks($start,$length,$filter= false,$token=null)
    {
        if(!\app\models\MongoSecurity::isAdminSafe($token)) return json_encode(array("status"=>"false","data"=>[]));
        if($start==0)$start++;
        $histories = \app\models\History::getUserBookHistoryAsArray($user = Yii::$app->user->identity->_id,$start,$length);
        $userId = Yii::$app->user->id;
        $currentBooks = \app\models\History::getAllUserBooks($userId);
        $recordsTotal = count($currentBooks);
        //print_r($histories);
        //die();
 
        return json_encode(array("status"=>"true","data"=>$histories,"recordsTotal"=>$recordsTotal,"recordsFiltered"=>$recordsTotal));
    }
    
    public function actionAdminbooks($start,$length,$filter= false,$token=null)
    {
        if(!\app\models\MongoSecurity::isAdminSafe($token)) return json_encode(array("status"=>"false","data"=>[]));
        if($start==0)$start++;
        $histories = \app\models\History::getBookHistoryAsArray($start,$length);
        $currentBooks = \app\models\History::getAllBooks();
        $recordsTotal = count($currentBooks);
        //print_r($histories);
        //die();
 
        return json_encode(array("status"=>"true","data"=>$histories,"recordsTotal"=>$recordsTotal,"recordsFiltered"=>$recordsTotal));
    }
    
    public function actionCount($filter= false)
    {
        
        $books = \app\models\Book::countBooks($filter);
        $total = ceil($books/3);
        return json_encode(array("status"=>"true","data"=>$total));
    }
    
    public function actionTogglestatus($id,$status,$token=null)
    {
        if(!\app\models\MongoSecurity::isAdminSafe($token)) return json_encode(array("status"=>"false","data"=>[]));
        $history = \app\models\History::findOne($id);
        
        //print_r($history);
        $collection = Yii::$app->mongodb->getCollection('History');
        $collection->update(['_id' => new \MongoId($id)],
                    [   'bookID' => $history->bookID,
                        'userID' => $history->userID,
                        'book' => $history->book,
                        'status' => $status,
                    ]);
        
        $book = \app\models\Book::findOne($history->bookID);
        
        if($status==1 && $status!=$history->status) $book->quantity=$book->quantity-1;
        else if($status==0 && $status!=$history->status) $book->quantity=$book->quantity+1;
        $bookcollection = Yii::$app->mongodb->getCollection('Book');
        $bookcollection->update(['_id' => $book->_id],
                    ['name' => $book->name,
                    'description' =>$book->description,
                    'pages' =>$book->pages,
                    'quantity' =>$book->quantity,
                    'image' =>$book->image,   
                    ]);
            
                
        return json_encode(array("status"=>"true"));
    }
    
    public function actionReserve($id)
    {
        //->where(['pages' => "400"])
        $status = true;
        $user;
        $book = \app\models\Book::findOne($id);
        $userId = Yii::$app->user->id;
        $currentBooks = \app\models\History::getCurrentUserBooks($userId);
        //print_r(count($currentBooks));
        //print_r($userId);die();
        if($book->quantity==0 || count($currentBooks)>=2) 
        {
            $status=false;
        }
        else
        {
            $user = User::findOne($userId);
            //print_r($user->_id->{'$id'});
            $collection = Yii::$app->mongodb->getCollection('History');
            $collection->insert(['userID' => $user->_id,
                                'bookID' =>new \MongoId($id),
                                'status' =>1,
                                'date' =>date("Y-m-d H:i:s"),
                                'book'=>
                                [
                                    '_id'=>$book->_id,
                                    'name'=>$book->name,
                                    'description'=>$book->description,
                                    'pages'=>$book->pages,
                                    'quantity'=>$book->quantity-1,
                                    'image'=>$book->image
                                ]
                    ]);
            
            $bookcollection = Yii::$app->mongodb->getCollection('Book');
            $bookcollection->update(['_id' => new \MongoId($id)],
                    ['name' => $book->name,
                    'description' =>$book->description,
                    'pages' =>$book->pages,
                    'quantity' =>$book->quantity-1,
                    'image' =>$book->image,   
                    ]);
            
            
        }
        return json_encode(array("status"=>$status,"data"=>$book));
    }
}
