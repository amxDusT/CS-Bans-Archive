<?php

$page = 'Thank You!';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

?>

<h2>Thank you for donating!</h2>
<h3>Your code:</h3>
<h3><?php echo Promocode::GetPromos(false, true, $model->type) . " : " . $model->code ?><h3>

<a href="https://www.csamx.net/threads/codes.66/">How to use?</a> | <a href="https://www.csamx.net/forums/help.24/">Forum Support</a>
| <a href="mailto:support@csamx.net">Email support</a>