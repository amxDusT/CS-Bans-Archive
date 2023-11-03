<?php

$page = 'Chat Manager';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('cm-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

$this->renderPartial('_search',array(
    'model'=>$model,
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	'id'=>'cm-grid',
    'dataProvider'=>isset($_GET['Chatmanager']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "cm_$data->id",
		"class" => "bantr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Pattern',
            'name' => 'pattern',
            'value' => 'CHtml::encode($data->pattern)',
        ),
		array(
			'header' => $model->block_type<2? 'Reason': ($model->block_type==4? 'Replace':',X'),
            'type' => 'raw',
            'id' => 'bReasonReplace',
			'name' => 'blocktype',
            'value' => '($data->block_type<2 || $data->block_type==4)? $data->reason:\',X\'',
        ),
        array(
			'header' => $model->block_type==1?'Time': ($model->block_type==2? 'Block':',X'),
            'type' => 'raw',
            'id' => 'bTime',
			'name' => 'blocktype',
            'value' => '($data->block_type<=2)? $data->time:\',X\'',
            ),

        array(
            'header' => CHtml::link(
                'Create',
                Yii::app()->createUrl('/chatmanager/create', array('block_type'=>$model->block_type) ),
                array(
                    'class' => 'btn btn-danger',
                )
                ),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array('style' => 'width:40px', 'class'=>'notHide'),
            'visible' => Webadmins::checkAccess('cm_view')
        )
	),
));

Yii::app()->clientScript->registerScript('hide-empty',"
    $('td:not(.notHide)').filter(function() {
        if($.trim($(this).text()) == ',X')
            $(this).hide();
    })
    $('th').filter(function() {
        if($.trim($(this).text()) == ',X')
            $(this).hide();
    })
");
?>