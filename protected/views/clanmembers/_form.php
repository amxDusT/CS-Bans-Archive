<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'clanmembers-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>TRUE,
));

?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'player_name', array('size'=>30,'maxlength'=>32)); ?>
	<?php echo $form->dropDownListRow($model, 'clanid', CHtml::listData(Clans::model()->findAll(),'id','clan_name')); ?>
	<?php echo $form->checkBoxRow($model, 'is_owner'); ?>
	
</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/clanmembers/index'),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
