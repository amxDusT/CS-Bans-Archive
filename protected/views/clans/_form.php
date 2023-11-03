<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'clans-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>TRUE,
));

?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<p class="note">Use <b>%name%</b> in substitution to the player's name on the 'Clan Structure' field</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'clan_name', array('size'=>60,'maxlength'=>64)); ?>
	<?php echo $form->textFieldRow($model, 'clan_tag', array('size'=>11,'maxlength'=>11)); ?>
	<?php echo $form->textFieldRow($model, 'clan_struct', array('size'=>30,'maxlength'=>32)); ?>
	
</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/clans/index'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
