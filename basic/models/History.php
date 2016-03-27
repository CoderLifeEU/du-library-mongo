<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "History".
 *
 * @property \MongoId|string $_id
 */
class History extends \yii\mongodb\ActiveRecord
{
    
    public $id;
    public $bookID;
    public $userID;
    public $book;
    public $status;
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'History';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
        ];
    }
    
    public static function getCurrentUserBooks($id)
    {
        return History::find()->where(['userID' => $id,'status' => 1])->all();
    }
    
    public static function getAllUserBooks($id)
    {
        return History::find()->where(['userID' => $id])->all();
    }
    
    public static function getAllBooks()
    {
        return History::find()->all();
    }
    
    public static function getUserBookHistory($id,$page)
    {
        //print_r($id); die();
        $pageSize=2;
        $offset = $page* $pageSize - $pageSize;
        
        $histories = History::find()
                ->where(['userID' => $id])
                ->offset($offset)
                ->limit($pageSize)
                ->all();
        return $histories;
    }
    
    public static function getUserBookHistoryAsArray($id,$page,$pageSize)
    {
        //print_r($page); die();
        $offset = $page* $pageSize - $pageSize;
        
        $histories = History::find()
                ->asArray()
                ->where(['userID' => $id])
                ->offset($offset)
                ->limit($pageSize)
                ->all();
        return $histories;
    }
    
    public static function getBookHistoryAsArray($page,$pageSize)
    {
        //print_r($page); die();
        $offset = $page* $pageSize - $pageSize;
        
        $histories = History::find()
                ->asArray()
                ->offset($offset)
                ->limit($pageSize)
                ->all();
        return $histories;
    }
}
