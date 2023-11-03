<?php

$page = 'Clans';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('clans-grid', {
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
	'id'=>'clans-grid',
    'dataProvider'=>isset($_GET['Clans']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "clan_$data->id",
		"class" => "bantr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Clan Name',
            'name' => 'clan_name',
            'type' => 'raw',
            'value' => 'CHtml::link(
                CHtml::encode($data->clan_name),
                Yii::app()->createUrl(\'/clanmembers/index?Clanmembers[clanid]=\'.$data->id),
            )',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
		array(
			'header' => 'Members',
			'type' => 'raw',
			'name' => 'members',
            'value' => 'Clanmembers::model()->count(\'`clanid` = :id\', array(\':id\' => $data->id))',
            /*'htmlOptions' => array(
                'style' => 'width: 130px'
            )*/
		),

    array(
        'header' => CHtml::link(
            'Create',
            Yii::app()->createUrl('/clans/create'),
            array(
                'class' => 'btn btn-danger',
            )
            ),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{update} {delete}',
        'htmlOptions' => array('style' => 'width:40px'),
        'visible' => Webadmins::checkAccess('bans_edit')
    )
	),
));
?>