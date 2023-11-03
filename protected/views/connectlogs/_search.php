<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get',
    'type'=>'inline'
));

echo $form->textFieldRow($model,'nick',array('maxlength'=>100));
echo $form->textFieldRow($model,'steamid',array('maxlength'=>20));
echo $form->textFieldRow($model,'ip',array('maxlength'=>100));

$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Search'
    ));

$this->endWidget();

?>