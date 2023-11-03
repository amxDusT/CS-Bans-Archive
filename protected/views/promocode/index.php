<?php

$page = 'Codes';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('clanmembers-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
/*
$this->renderPartial('_search',array(
    'model'=>$model,
));
*/
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	'id'=>'codes-grid',
    'dataProvider'=> $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "code_$data->id",
		"class" => ( $data->used==1 || $data->expires <= time() )? "codetr success":"codetr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Code Name',
            'name' => 'code_name',
            'value' => 'CHtml::encode($data->codename)',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
		array(
			'header' => 'Type',
			'type' => 'raw',
			'name' => 'type',
            'value' => 'Promocode::GetPromos( false, true, $data->type )',
		),
        array(
            'header' => 'Expires',
            'name' => 'expires',
            'value' => 'date("d.m.Y H:i", $data->expires)',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
    array(
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{view}',
        'htmlOptions' => array('style' => 'width:40px;'),
    )
	),
));
?>