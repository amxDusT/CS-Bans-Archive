<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add Server';
$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'Add Server'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'srvadd'));
?>

<h2>Add Server</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'activebtn' => 'srvadd')); ?>