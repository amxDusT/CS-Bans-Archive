<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add Player';

$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'Add Clan'
);

//$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'addclan'));
?>

<h2>Add Clan</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'activebtn' => 'addclan')); ?>