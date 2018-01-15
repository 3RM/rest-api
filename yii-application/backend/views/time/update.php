<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Time */

$this->title = 'Update Time: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Times', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="time-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
