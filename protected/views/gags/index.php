<?php

$page = 'Gags';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('viewdetail', "
$('.gagtr ').on('click', function(e){
    var target = $(e.target);
    if(target.is('.icon-pencil') || target.is('.icon-trash') )
        return;
	$('#loading').show();
	var aid = this.id.substr(4);
	$.post('".Yii::app()->createUrl('gags/viewgg/')."', {'aid': aid}, function(data){
		eval(data);
    });
})
");
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('gags-grid', {
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
	'id'=>'gags-grid',
    'dataProvider'=>isset($_GET['Gags']) ? $model->search() : $dataProvider,
    'enableSorting' => false,
	'summaryText' => 'Showing {start} of {end} from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
        "id" => "gag_$data->id",
        "style" => "cursor:pointer;",
		"class" => (($data->expired_time > 0 && $data->expired_time < time()) || $data->expired_time == -1 ) ? "gagtr success" : "gagtr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(
        array(
            'header' => 'Date',
            'name' => 'create_time',
            'value' => 'date("d.m.Y H:i", $data->create_time)',
            'htmlOptions' => array('style' => 'width:100px'),
        ),
	
	array(
		'header' => 'Name',
		'type' => 'raw',
		'name' => 'name',
		'value' => '$data->country . " " . CHtml::encode($data->name)'
	),


    array(
        'header' => 'Admin',
        'type' => 'raw',
        'name' => 'admin_name',
        'value' => 'CHtml::encode(mb_substr($data->admin_name, 0, 32, "UTF-8"))',
        //'htmlOptions' => array('style' => 'width: 130px')
    ),
	array(
		'header' => 'Reason',
		'name' => 'reason',
		'value' => '$data->reason ? CHtml::encode($data->reason) : ""',
		//'htmlOptions' => array('style' => 'width:100px'),
    ),
    array(
		'header' => 'Length',
        //'value' => '$data->expired_time'
        'value' => '($data->expired_time==-1? "Expired" : ($data->expired_time==0? "Permanent" : Prefs::date2word(($data->expired_time - $data->create_time)/60) . ($data->expired_time<time()? " (Expired)":""))) ',
		//'htmlOptions' => array('style' => 'width:200px'),
	),
        
        array(
            'header' => CHtml::link(
                'Create',
                Yii::app()->createUrl('/gags/create'),
                array(
                    'class' => 'btn btn-danger',
                )
                ),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array('style' => 'width:40px'/*, 'onclick' => 'event.stopPropagation()'*/),
            'visible' => !Yii::app()->user->isGuest
        )
        ),
));
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal',
	array(
		'id'=>'gagDetails',
		'htmlOptions' => array(
			'style' => 'width: 600px; margin-left: -300px; min-height: 400px'
		)
)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal" rel="tooltip" data-placement="left" title="Close">&times;</a>
    <h4>Gag Details</h4>
</div>
<div class="modal-body" style="min-height: 350px">
	<h3>Info</h3>
    <div id="gagInfo"></div>
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