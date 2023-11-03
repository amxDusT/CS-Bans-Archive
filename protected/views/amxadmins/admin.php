<?php
/**
 * Управление AmxModX админами
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - AMXMODX Admins';
$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Add AMXMODX Admin','url'=>array('create')),
);
?>

<h2>Manage AMXMODX Admins</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'type' => 'bordered stripped',
	'id'=>'amxadmins-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableSorting' => FALSE,
	'summaryText' => 'Showning {start} of {end} admins from {count}. Page {page} of {pages}',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
	'rowHtmlOptionsExpression'=>'array(
		"class" => $data->expires != 0 && $data->expires <= time() ? "error" : ""
	)',
	'columns'=>array(
		'username',
		array(
			'name' => 'access',
			'value' => 'Amxadmins::GetMyFlags($data->access)'
		),
		array(
			'name' => 'expires',
			'type' => 'raw',
			'value' => 'Amxadmins::GetDays($data->expires)'
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
