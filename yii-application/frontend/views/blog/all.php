<?php

//$blogs = $dataProvider->getModels();

?>

<div class="body-content">
	<?= \yii\widgets\ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => '_one',
	]); ?>
</div>
