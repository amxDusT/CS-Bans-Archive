<?php


$page = 'Reset';

$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array($page),
));

echo "<h2> Reset Password for ". CHtml::encode($model->username) . "</h2>";
echo $this->renderPartial('_form_password', array('model'=>$model));
?>
