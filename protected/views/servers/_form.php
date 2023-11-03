<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'servers-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>TRUE,
));

?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'hostname', array('size'=>32,'maxlength'=>32, 'disabled'=>'disabled', 'placeholder'=>'Automatically Updated')); ?>
	<?php echo $form->textFieldRow($model, 'address', array('size'=>32,'maxlength'=>16)); ?>
	<?php echo $form->textFieldRow($model, 'port', array('size'=>32,'maxlength'=>6)); ?>
	
	<?php echo $form->textFieldRow($model, 'start_cmd', array('size'=>64,'maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model, 'stop_cmd', array('size'=>64,'maxlength'=>255)); ?>
	
</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/servers/index'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
