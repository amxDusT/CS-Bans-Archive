<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add Player';

$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'Add Zombie Player'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'zpaddplayer'));
?>

<h2>Add Zombie player</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'activebtn' => 'zpaddplayer')); ?>