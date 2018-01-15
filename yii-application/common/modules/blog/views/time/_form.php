<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\Time */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'time')->widget(DateControl::className(), ['type' => DateControl::FORMAT_TIME]) ?>

    <?= $form->field($model, 'date')->widget(DateControl::className(), []) ?>

    <?= $form->field($model, 'datetime')->widget(DateControl::className(), [
    		'type' => DateControl::FORMAT_DATETIME,
    		/*'displayFormat' => 'php:d-M-Y'*/
    	]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
