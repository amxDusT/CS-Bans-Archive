<?php

$page = 'Connect Logs';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('connect-grid', {
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
	'id'=>'connect-grid',
    'dataProvider'=>isset($_GET['Connectlogs']) && $model->isEmpty!==true ? $model->search() : $dataProvider,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "connect_$data->id",
		"class" => "connecttr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Date',
            'name' => 'date',
            'value' => '$data->tdate==0? date("d.m.Y H:i", $data->date):date("d.m.Y H:i", $data->tdate)',
            'htmlOptions' => array('style' => 'width:100px'),
        ),
	
	array(
		'header' => 'Nick',
		'type' => 'raw',
		'value' => 'CHtml::encode($data->nick)'
	),

    array(
        'header' => 'SteamID',
        'type' => 'raw',
        'value' => '$data->steamid',
        'htmlOptions' => array(
            'style' => 'width: 150px'
        )
    ),
    array(
		'header' => 'IP',
		'type' => 'raw',
        'value' => '$data->ip',
        'htmlOptions' => array(
            'style' => 'width: 130px'
        )
        ),
    array(
        'header'=>'Played Time',
        'type' => 'raw',
        'value' => '$data->pl==0? Prefs::date2word($data->played_time, false, true) : Prefs::date2word($data->pl, false, true)',
    ),
    array(
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'header' => '',
        'template'=>'{view}',
        'visible'=>Webadmins::checkAccess('bans_edit')
    )
	),
));
?>