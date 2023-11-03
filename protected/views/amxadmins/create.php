<?php
/**
 * Вьюшка добавления админа серверов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$this->pageTitle = Yii::app()->name . ' :: Admin Panel - Add AMXMODX Admin';
$this->breadcrumbs = array(
	'Admin Panel' => array('/admin/index'),
	'Add AMXMODX Admin'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'servamxadmins'));

$this->menu=array(
	array('label'=>'Admin Management', 'url'=>array('admin')),
);
?>

<h2>Add AMXMODX Admin</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'webadmins' => new Webadmins)); ?>