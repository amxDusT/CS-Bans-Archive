<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Server ' . $model->hostname;
$this->breadcrumbs=array(
	'ServerList'=>array('index'),
	'Server #'.$model->id=>array('view','id'=>$model->id),
	'Edit',
);

?>

<h2>Edit Server #<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>