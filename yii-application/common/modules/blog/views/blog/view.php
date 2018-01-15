<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Carousel;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= "User id: ".\Yii::$app->user->id."<br>" ?>
    <?= "Model user - '".$model->author->username."'.    id: ".$model->author->id."<br>" ?>

    <?php if(\Yii::$app->user->can('updatePost', ['author_id' => $model->user_id])): ?>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'text:ntext',
            'url:url',
            'status_id',
            'sort',
            'author.username',
            'author.email',
            'tagsAsString',
            'smallImage:image',
        ],
    ]) ?>


    <?php 
    /*
    $fotorama = \metalguardian\fotorama\Fotorama::begin(
        [
            'options' => [
                'loop' => true,
                'hash' => true,
                'ratio' => 800/600,
            ],
            'spinner' => [
                'lines' => 20,
            ],
            'tagName' => 'span',
            'useHtmlData' => false,
            'htmlOptions' => [
                'class' => 'custom-class',
                'id' => 'custom-id',
            ],
        ]
    ); */
    if($model->images){
        foreach($model->images as $one){
            echo $one->imageUrl."<br>";
            $img_src[] = [
                'content' => \yii\helpers\Html::img($one->imageUrl,['alt' => $one->alt]),
                'options' => ['style'=>'height: 300px'],
            ];
        }
    }
    /* $fotorama->end(); */
    ?>
    <?php if(isset($img_src)): ?>
    <div class="row">
        <div class="col-lg-6">
        <?= Carousel::widget([
            'items' => $img_src,          
                'options' => [
                    'data-interval => 5000',
                    'style' => 'height: 300px'
                ],
                'controls' => [
                    '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                    '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
                ]    
        ]);
        ?>
        </div>
    </div>
    <?php endif; ?>
</div>
