<?php

?>

<div class="col-lg-12">
	<h3><?= $model->title; ?><span class="badge badge-pill badge-default"><?= $model->author->username; ?></span></h3>
	<?= $model->text?>
	<br>
	<?= \yii\bootstrap\Html::a('Подробнее', ['blog/one', 'url' => $model->url], ['class' => 'btn btn-success btn-xs']); ?>
</div>