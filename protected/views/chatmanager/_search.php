<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get',
    'type'=>'inline'
));
Yii::app()->clientScript->registerScript('search-cm-script',"

    $(document).on('change', '#Chatmanager_block_type', function() {
        $('.btn.btn-primary').click();
    });	

");


echo CHtml::dropDownList('Chatmanager[block_type]', $model->block_type, Chatmanager::getBlockTypes());
echo $form->textFieldRow($model,'pattern',array('maxlength'=>100));

$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Submit'
    ));

$this->endWidget();

?>