<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get',
    'type'=>'inline'
));

echo $form->textFieldRow($model,'hostname',array('maxlength'=>100));
echo $form->textFieldRow($model,'address',array('maxlength'=>20));

$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Submit'
    ));

$this->endWidget();

?>