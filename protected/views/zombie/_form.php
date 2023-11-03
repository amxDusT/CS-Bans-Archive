<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'zombie-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>TRUE,
));

?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'player_nick', array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model, 'player_steamid', array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model, 'player_ip', array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model, 'message', array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model, 'ammo', array('size'=>11,'maxlength'=>11)); ?>
	
	
</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/zombie/index'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
