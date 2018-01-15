<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Times';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Time', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'time:datetime',
            'date',
            'datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
