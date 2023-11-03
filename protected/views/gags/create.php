<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add Player';
?>

<h2>Add Gag player</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'activebtn' => 'gagplayer')); ?>