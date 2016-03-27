<?php

namespace app\models;

use Yii;
use yii\mongodb\Query;

/**
 * This is the model class for collection "Book".
 *
 * @property \MongoId|string $_id
 */
class Book extends \yii\mongodb\ActiveRecord
{
    
    public $id;
    public $name;
    public $description;
    public $pages;
    public $quantity;
    public $image;
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['dudb','Book'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'Name',
            'Description',
            'Pages',
            'Quantity',
            'Image'
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
            'Name' => 'Name',
        ];
    }
    
    public static function  getBooks($page,$pageSize,$filter)
    {
        /*$query = new Query;
        // compose the query
        $query->select(['Name', 'description'])
            ->from('Book')
            ->limit($limit);
        // execute the query
        $rows = $query->all();
        
        return $rows;*/
        $offset = $page* $pageSize - $pageSize;
        
        $books = \app\models\Book::find()
                //->where(['like', 'Name', "Bas"],['like', 'Description', "fund"])
                ->offset($offset)
                ->limit($pageSize)
                ->all();
        return $books;
    }
    
    public static function countBooks($filter)
    {
        $query = new Query;
        // compose the query
        $query->select(['count(*) as count'])
            ->from('Book');
        // execute the query
        $rows = $query->count();
        return $rows;
    }
    
    public static function getUserBooks($histories)
    {
        $where =[];
        for($i=0;$i<count($histories);$i++)
        {
            array_push($where, $histories[$i]->bookID);
        }
        $books = Book::find()->where(['_id' => $where])->all();
        return $books;
    }
}
