<?php

/* @var $this yii\web\View */

$this->title = 'My Books';
$this->registerCssFile("@web/css/datatables.min.css", [
    //'depends' => [BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');
$this->registerJsFile('@web/js/bootpag.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/handlebars-v4.0.5.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/datatables.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/my.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<style>
    #example_filter{
        display:none;
    }
</style>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello from My Books</h1>

        <p class="lead">My books</p>

    </div>

    <div class="body-content">
        
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Reservation Number</th>
                <th>Book</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Reservation Number</th>
                <th>Book</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <th>Status</th>
            </tr>
        </tbody>
    </table>
        

        
        
        <div class="row text-center">
            <div id="page-selection"></div>
            
        </div>

    </div>
</div>

<script id="entry-template" type="text/x-handlebars-template">
    {{#each books}}
    <div class="col-sm-6 col-md-4">
              <div class="thumbnail" >
                {{#if this.image}}
                    <img class="img-thumbnail img-responsive" src="{{this.image}}" alt="..." style="height:300px">
                {{else}}
                    <img class="img-thumbnail img-responsive" src="http://vintageprintable.com/wp-content/uploads/2011/01/Printed%20Matter%20-%20Book%20Cover%20-%20Les%20Dames%20du%20Bois.jpg" alt="..." style="height:300px">
                {{/if}}
                <div class="caption">
                  <h3>{{this.name}}</h3>
                  <p>{{this.description}}</p>
                  <p>Quantity:<strong class="book-quantity">{{this.quantity}}</strong></p>
                  <p class="text-center"><a href="#" class="btn btn-primary btn-reserve" data-id="{{this.id.$id}}"role="button">Reserve</a> <a href="#" class="btn btn-default btn-check" role="button">Check</a></p>
                </div>
              </div>
            </div>
  {{/each}}
</script>