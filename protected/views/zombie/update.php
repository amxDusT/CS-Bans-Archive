<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Player Zombie ' . $model->player_nick;
$this->breadcrumbs=array(
	'Zombie'=>array('index'),
	'Edit',
);

?>

<h2>Edit Zombie Player <?php echo CHtml::encode($model->player_nick); ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>