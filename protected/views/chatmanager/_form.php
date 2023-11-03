<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'zombie-form',
	'type'=>'horizontal',
));
Yii::app()->clientScript->registerScript('live-cm-script',"

        $(document).on('change', '#Chatmanager_block_type', function() {
			$('.check-reason').toggle( $(this).val() < 2 || $(this).val() == 4 );
			
			$('.check-time').toggle( $(this).val() == 1 || $(this).val() == 2 ); 

			if( $(this).val() == 4 )
				$('label[for=\"Chatmanager_reason\"]').text('Replace');
			else
				$('label[for=\"Chatmanager_reason\"]').text('Reason');
			
				if( $(this).val() == 2 )
				$('label[for=\"Chatmanager_time\"]').text('Block');
			else
				$('label[for=\"Chatmanager_time\"]').text('Time');
        });	

	");
$first_block = $model->block_type;
?>

<p class="note">Fields marked <span class="required">*</span> are required.</p>
<fieldset>
	<?php echo $form->errorSummary($model); ?>
	
	
	<?php echo $form->dropDownListRow($model, 'block_type', Chatmanager::getBlockTypes()); ?>
	<?php echo $form->textFieldRow($model, 'pattern', array('size'=>64,'maxlength'=>100)); ?>

	<div class="control-group check-reason" style="<?php echo ($model->block_type<2 || $model->block_type==4)? 'display: block;':'display: none;' ?>" >
		<?php echo CHtml::label($model->block_type==4? 'Replace':'Reason', 'Chatmanager_reason', array('class' => 'control-label'));?>
		<div class="controls">
			<?php echo CHtml::textField('Chatmanager[reason]', $model->reason )?>
		</div>
	</div>

	<div class="control-group check-time" style="<?php echo ($model->block_type==2 || $model->block_type==1)? 'display: block;':'display: none;' ?>" >
		<?php echo CHtml::label($model->block_type==2? 'Block':'Time', 'Chatmanager_time', array('class' => 'control-label') );?>
		<div class="controls">
			<?php echo CHtml::textField('Chatmanager[time]', $model->time )?>
		</div>
	</div>

</fieldset>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Refresh'));
		?>
		<?php echo CHtml::link(
				'Cancel',
				Yii::app()->createUrl('/chatmanager/index?Chatmanager%5Bblock_type%5D='.$first_block),
				array(
					'class' => 'btn btn-danger'
				)
			);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
