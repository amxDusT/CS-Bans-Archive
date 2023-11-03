<?php

$page = 'Code';
$this->pageTitle = Yii::app()->name . ' - ' . $page . ' - Code Details ' . $model->codename . " | ".Promocode::GetPromos( false, true, $model->type );
$this->breadcrumbs=array(
	$page=>array('index'),
	$model->codename,
);
?>

<h2>Code Details <i><?php echo CHtml::encode(Promocode::GetPromos( false, true, $model->type )); ?></i></h2>
<div style="float: right">
	<?php
	if(Webadmins::checkAccess('code_edit')):
	echo CHtml::ajaxLink(
		'<i class="icon-remove"></i>',
		$this->createUrl('/promocode/delete', array('id' => $model->id)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {if(!confirm("Remove code '.$model->codename . " | " . Promocode::GetPromos( false, true, $model->type ).'?")) {return false;} }',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/promocode/index').'";}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Remove',
		)
	);
	endif;
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'type' => array('condensed', 'bordered'),
	'htmlOptions' => array('style'=>'text-align: left'),
	'attributes'=>array(
		array(
			'name' => 'Code Name',
			'value' => CHtml::encode($model->codename )
		),
		array(
			'name' => 'Code',
			'type' => 'raw',
			'value' => CHtml::encode( $model->code )
		),
		array(
			'name' => 'Expires',
			'value' => date("d.m.Y H:i", $model->expires)
		),
		array(
			'name' => 'Redeemed',
			'value' => $model->used? date("d.m.Y H:i", $model->redeemedAt):"No"
        ),
        array(
			'name' => 'Redeemed By',
			'value' => $model->used? CHtml::encode( $model->redeemedBy ):"NONE"
        ),
	),
)); ?>