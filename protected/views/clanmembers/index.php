<?php

$page = 'Clan Members';
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

$this->renderPartial('_search',array(
    'model'=>$model,
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	'id'=>'clanmembers-grid',
    'dataProvider'=>isset($_GET['Clanmembers']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "clanmember_$data->id",
		"class" => "bantr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Player',
            'name' => 'player_name',
            'value' => 'CHtml::encode($data->player_name)',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
        array(
            'header' => 'Clan',
            'name' => 'clan_name',
            'value' => 'CHtml::encode(Clanmembers::getClanName($data->clanid))',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
		array(
			'header' => 'Role',
			'type' => 'raw',
			'name' => 'role',
            'value' => '$data->is_owner==1? \'Owner\':\'Member\'',
		),

    array(
        'header' => CHtml::link(
            'Create',
            Yii::app()->createUrl('/clanmembers/create'),
            array(
                'class' => 'btn btn-danger',
            )
            ),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{update} {delete}',
        'htmlOptions' => array('style' => 'width:40px;'),
        'visible' => Webadmins::checkAccess('bans_edit')
    )
	),
));
?>