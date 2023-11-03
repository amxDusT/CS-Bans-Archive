<?php


$page = 'Reset';

$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array($page),
));

echo "<h2> Reset Password Request </h2><br>";
echo $this->renderPartial('_form_username', array('model'=>$model));
?>
