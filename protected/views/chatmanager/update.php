<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Pattern' . $model->pattern;
$this->breadcrumbs=array(
	'Chatmanager'=>array('index'),
	'Edit',
);

?>

<h2>Edit Pattern #<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>