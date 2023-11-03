<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Player Gag ' . $model->name;
$this->breadcrumbs=array(
	'Gaglist'=>array('index'),
	'Edit',
);

?>

<h2>Edit Gag #<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>