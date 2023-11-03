<?php

$page = 'Servers';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);


Yii::app()->clientScript->registerScript('viewdetail', "
$('.servertr').each( function(){
    var aid = this.id.substr(4);      
    $.post('".Yii::app()->createUrl('servers/info/')."', {'aid': aid}, function(data){
        eval(data);
    });
});
$('.servertr').on('click', function(){
    
	$('#loading').show();
	var aid = this.id.substr(4);
	$.post('".Yii::app()->createUrl('servers/viewserver/')."', {'aid': aid}, function(data){
		eval(data);
    });
})
");

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('servers-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

$this->renderPartial('_search',array(
    'model'=>$model,
));
//$server = $models->GetInfo();
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	'id'=>'servers-grid',
    'dataProvider'=>isset($_GET['Servers']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
    ),
	'rowHtmlOptionsExpression'=>'array(
        "id" => "srv_$data->id",
        "style" => "cursor:pointer;",
		"class" => "servertr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Hostname',
            'name' => 'hostname',
            'value' => 'raw',
            'value' => 'CHtml::encode($data->hostname)',
        ),
		array(
			'header' => 'Server IP',
			'type' => 'raw',
			'name' => 'serverip',
            'value' => '$data->address.":".$data->port',
		),

        array(
            'header' => 'Map',
            'type' => 'raw',
            'htmlOptions' => array(
                'class' => 'map'
            ),
            'value' => '"loading.."',
        ),

        array(
            'header' => 'Players',
            'type' => 'raw',
            'name' => 'players',
            'htmlOptions' => array(
                'class' => 'players'
            ),
            'value' => '"loading.."',
        ),

        array(
            
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
            'htmlOptions' => array(
                'style' => 'width:40px; cursor: auto;',
                'onclick' => 'event.stopPropagation()'
            ),
        )
        ),
));


?>
<?php $this->beginWidget('bootstrap.widgets.TbModal',
	array(
		'id'=>'serverssDetail',
		'htmlOptions' => array(
			'style' => 'width: 600px; margin-left: -300px; min-height: 400px'
		)
)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal" rel="tooltip" data-placement="left" title="Close">&times;</a>
    <h4>Server Details</h4>
</div>
<div class="modal-body" style="min-height: 350px">
	<h3>Info</h3>
    <div id="serverInfo"></div>
</div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>'#',
        'htmlOptions'=>array(
			'data-dismiss'=>'modal',
		),
    )); ?>
</div>
<?php $this->endWidget(); ?>