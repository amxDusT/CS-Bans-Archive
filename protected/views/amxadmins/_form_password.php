<?php
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'reset-form',
	'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
echo $form->passwordFieldRow(
    $model,
    'password',
    array(
        'value'=>'',
        'placeholder'=>'New Password'
    )
);
?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Save',
    )); ?>
</div>

<?php $this->endWidget();?>