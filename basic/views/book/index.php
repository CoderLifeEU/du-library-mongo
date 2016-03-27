<?php

/* @var $this yii\web\View */

$this->title = 'Choose and reserve book';
$this->registerJsFile('@web/js/bootpag.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/handlebars-v4.0.5.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/book.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello from Book Controller</h1>

        <p class="lead">Choose and reserve book </p>

    </div>

    <div class="body-content">
        
        
        

        <div class="row" id="content">
            <div class="col-sm-6 col-md-4">
              <div class="thumbnail" >
                <img class="img-thumbnail img-responsive" src="http://ecx.images-amazon.com/images/I/919-FLL37TL.jpg" alt="..." style="height:300px">
                <div class="caption">
                  <h3>Thumbnail label</h3>
                  <p>...</p>
                  <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
              </div>
            </div>
            
            <div class="col-sm-6 col-md-4">
              <div class="thumbnail">
                <img src="http://ecx.images-amazon.com/images/I/41cSHC9y2SL.jpg" alt="..." style="height:300px">
                <div class="caption">
                  <h3>Thumbnail label</h3>
                  <p>...</p>
                  <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
              </div>
            </div>
            
            <div class="col-sm-6 col-md-4">
              <div class="thumbnail">
                <img src="http://i.dailymail.co.uk/i/pix/2013/08/14/article-2392919-1B4AF3AF000005DC-754_634x929.jpg" alt="..." style="height:300px">
                <div class="caption">
                  <h3>Thumbnail label</h3>
                  <p>...</p>
                  <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                </div>
              </div>
            </div>
        </div>
        
        <div class="row text-center">
            <div id="page-selection"></div>
            
        </div>

    </div>
</div>

<script id="entry-template" type="text/x-handlebars-template">
    {{#each books}}
    <div class="col-sm-6 col-md-4">
              <div class="thumbnail book-item" >
                {{#if this.image}}
                    <img class="img-thumbnail img-responsive" src="{{this.image}}" alt="..." style="height:300px">
                {{else}}
                    <img class="img-thumbnail img-responsive" src="http://vintageprintable.com/wp-content/uploads/2011/01/Printed%20Matter%20-%20Book%20Cover%20-%20Les%20Dames%20du%20Bois.jpg" alt="..." style="height:300px">
                {{/if}}
                <div class="caption">
                  <h3 class="clamp-text">{{this.name}}</h3>
                  <p class="clamp-text">{{this.description}}</p></span><span class=" text-center glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" title="{{this.description}}"></span>
                  <p>Quantity:<strong class="book-quantity">{{this.quantity}}</strong></p>
                  <p class="text-center"><a href="#" class="btn btn-primary btn-reserve" data-id="{{this.id.$id}}"role="button">Reserve</a></p>
                </div>
              </div>
            </div>
  {{/each}}
</script>