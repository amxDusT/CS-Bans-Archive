<?php

$page = 'Zombie';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('zombie-grid', {
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
	'id'=>'zombie-grid',
    'dataProvider'=>isset($_GET['Zombie']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "zombie_$data->id",
		"class" => "bantr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Nick',
            'name' => 'player_nick',
            'value' => 'CHtml::encode($data->player_nick)',
            //'htmlOptions' => array('style' => 'width:100px'),
        ),
        array(
            'header' => 'SteamID',
            'name' => 'player_steamid',
            'value' => '$data->player_steamid',
            'visible' => Webadmins::checkAccess('zombie_edit')
        ),
        array(
            'header' => 'IP',
            'name' => 'player_ip',
            'value' => '$data->player_ip',
            'visible' => Webadmins::checkAccess('zombie_edit')
        ),
		array(
			'header' => 'Ammo',
			'type' => 'raw',
			'name' => 'ammo',
            'value' => '$data->ammo',
        ),
        array(
			'header' => 'Total Ammo',
			'type' => 'raw',
			'name' => 'total_ammo',
            'value' => '$data->total_ammo',
        ),

    array(
        'header' => CHtml::link(
            'Create',
            Yii::app()->createUrl('/zombie/create'),
            array(
                'class' => 'btn btn-danger',
            )
            ),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'template' => '{update} {delete}',
        'htmlOptions' => array('style' => 'width:40px'),
        'visible' => Webadmins::checkAccess('zombie_edit')
    )
	),
));
?>