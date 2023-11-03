<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'post',
    'type'=>'vertical'
));
echo "We will send an email with a reset link for your nick.<br>";
echo $form->errorSummary($model);
echo $form->textField($model, 'email',array('placeholder'=>'Nick\'s email','maxlength'=>100) );
//echo $form->textFieldRow($model,'username',array('maxlength'=>100));
echo "<div class='form-actions'>";
$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Reset'
    ));
echo "</div>";
$this->endWidget();

?>
