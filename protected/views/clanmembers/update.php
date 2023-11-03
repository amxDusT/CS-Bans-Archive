<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Clan Member ' . $model->player_name;
$this->breadcrumbs=array(
	'Clan Member'=>array('index'),
	'Edit',
);

?>

<h2>Edit Clan Member #<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>