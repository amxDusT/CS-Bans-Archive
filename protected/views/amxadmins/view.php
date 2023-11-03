<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Edit Admin';
$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'AMXMODX Admins' => array('admin'),
	'Admin ' . $model->username
);
$this->renderPartial('/admin/mainmenu', array('active' =>'server', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Add AMXMODX Admin', 'url'=>array('create')),
	array('label'=>'Control AMXMODX Admins', 'url'=>array('admin')),
);
?>
<h2>Admin Details &laquo;<?php echo CHtml::encode($model->username); ?>&raquo;</h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' => 'flags',
			'value' => Amxadmins::getAuthType($model->flags)
		),
		'username',
		array(
			'name' => 'access',
			'value' => Amxadmins::GetMyFlags( $model->access )
		),
		array(
			'name' => 'created',
			'type' => 'datetime',
			'value' => $model->created
		),
		array(
			'name' => 'expires',
			'value' => $model->expires == 0 ? 'Never' : date('d.m.Y H:i', $model->expires)
		),
		array(
			'name' => 'days',
			'type' => 'raw',
			'value' => Amxadmins::GetDays($model->expires)
		),
	),
)); ?>
