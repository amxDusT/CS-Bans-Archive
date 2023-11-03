<?php
$this->pageTitle = Yii::app()->name . ' :: Create Codes';
$this->breadcrumbs = array(
	'Promo Codes' => array('/promocode/index'),
	'Add PromoCode'
);

$this->renderPartial('/admin/mainmenu', array('active' =>'main', 'activebtn' => 'btnaddpromo'));

?>

<h2>Create CODE</h2>
<?php
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'promocode-form',
        'type'=>'horizontal',
        'enableAjaxValidation'=>false,
    ));
    echo $form->errorSummary($model);

    echo $form->textFieldRow($model,'codename',array('size'=>32,'maxlength'=>32));

    echo $form->dropDownListRow(
		$model,
		'type',
		Promocode::GetPromos( true ),
	);?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=> 'Create'));
    ?>
    <?php echo CHtml::link(
            'Cancel',
            Yii::app()->createUrl('/promocode/index'),
            array(
                'class' => 'btn btn-danger'
            )
        );
    ?>
</div>
<?php $this->endWidget();?>