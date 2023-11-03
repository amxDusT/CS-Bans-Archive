<?php

$this->pageTitle = Yii::app()->name .' :: Admin Panel - Edit Clan ' . $model->clan_name;
$this->breadcrumbs=array(
	'Clan'=>array('index'),
	'Edit',
);

?>

<h2>Edit Clan #<?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>