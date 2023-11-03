<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Edit Admin';
$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'AMXMODX Admins' => array('admin'),
	'Edit Admin ' . $model->username
);

$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Add AMXMODX Admin', 'url'=>array('create')),
	array('label'=>'Control AMXMODX Admins', 'url'=>array('admin')),
);
?>

<h2>Edit AMXMODX Admin <?php echo CHtml::encode($model->username); ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>