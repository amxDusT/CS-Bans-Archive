<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get',
    'type'=>'inline'
));

echo $form->textFieldRow($model,'clan_name',array('maxlength'=>100));

$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Submit'
    ));

$this->endWidget();

?>