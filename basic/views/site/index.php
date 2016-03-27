<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$this->registerCssFile("@web/css/hover.css", [
    //'depends' => [BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');
?>
<div class="site-index">

    <div class="hero-wrap">
    <div class="description">
        <h1>
            <p>DU Library</p>
            <p>Dive into amazing book world</p>
        </h1>
        <h2>Reserve awesome book in just a few clicks</h2>

        <?php if(Yii::$app->user->isGuest) : ?>
        <a class="btn btn-x-lg btn-primary" href="index.php?r=site%2Flogin">SIGN UP (IT'S FREE!)</a>
        <?php else: ?>
        <a class="btn btn-x-lg btn-primary" href="index.php?r=book%2Findex">RESERVE BOOKS</a>
        <?php endif; ?>
  
        <?php $server = "mongodb://root:9892735aedg@104.131.35.197:27017/dudb";
 
/**
    Remove username and password, if you want
    to connect to an unauthenticated MongoDB database.
    See the example code below
*/
// $server = "mongodb://localhost:27017/university";
 
// Connecting to server
$c = new MongoClient( $server );
 
if($c->connected)
    echo "Connected successfully";
else
    echo "Connection failed";
 
?>
        </div>
    </div>

    <div class="body-content">

        

    </div>
</div>
