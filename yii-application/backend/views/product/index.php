<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Sklad;
use common\models\Product;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'sklad_id',
                'value' => 'skladName',
                'filter' => Sklad::getList()
            ],
            [
                'attribute' => 'sklad_name',
                'value' => 'skladName',
            ],
            'title',
            'cost',
            [
                'attribute' => 'date',
                'format' => 'date',
                'value' => 'date',
                'filter' => \kartik\field\FieldRange::widget([
                    'type' => \kartik\field\FieldRange::INPUT_WIDGET,
                    'model' => $searchModel,
                    'attribute1' => 'from_date',
                    'attribute2' => 'to_date',
                    'widgetClass' => \kartik\datecontrol\DateControl::classname(),
                    'widgetOptions1' => [                        
                        'saveFormat' => 'php:U',                        
                    ],
                    'widgetOptions2' => [
                        'saveFormat' => 'php:U',                        
                    ],
                ])
            ],
            //'date:date',
            [
                'attribute' => 'type_id',
                'value' => 'typeName',
                'filter' => Product::getTypeList()
            ],
            // 'desc:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>