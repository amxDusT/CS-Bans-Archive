<?php

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add Pattern';
?>

<h2>Add Pattern</h2>
<?php
$model->block_type = $_GET['block_type'];
echo $this->renderPartial('_form', array('model'=>$model)); 
?>