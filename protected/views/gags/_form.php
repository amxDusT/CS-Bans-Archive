<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'gags-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>TRUE,
));

?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'name', array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model, 'steamid', array('size'=>35,'maxlength'=>35)); ?>
	<?php echo $form->textFieldRow($model, 'ip', array('size'=>32,'maxlength'=>32)); ?>


    <?php //echo $form->textFieldRow($model, 'admin_name', array('size'=>60,'maxlength'=>100)); ?>
	<?php //echo $form->textFieldRow($model, 'admin_steamid', array('size'=>35,'maxlength'=>35)); ?>
	<?php //echo $form->textFieldRow($model, 'admin_ip', array('size'=>32,'maxlength'=>32)); ?>
	
	<?php echo $form->textFieldRow($model, 'reason', array('size'=>64,'maxlength'=>64)); ?>

	<?php echo $form->error($model,'reason'); ?>
	<?php echo $form->dropDownListRow($model, 'block_type', array(
		0 => 'Chat',
		1 => 'Voice',
		2 => 'Chat + Voice'
	)); ?>
	<?php echo $form->dropDownListRow($model, 'length', Gags::getGagLength()); ?>
	
</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/gags/index'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
