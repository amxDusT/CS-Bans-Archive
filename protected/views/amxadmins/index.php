<?php


$page = 'Administrators';

$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array($page),
));

Yii::app()->clientScript->registerScript('viewdetail', "
$('.admintr').on('click', function(){
	$('#loading').show();
	var aid = this.id.substr(6);
	$.post('".Yii::app()->createUrl('amxadmins/view/')."', {'aid': aid}, function(data){
		eval(data);
	});
})
");
?>
<h2><?php echo $page; ?></h2>

<?php
 	$this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider'=>$admins,
	'type'=>'striped bordered condensed',
	'id' => 'admins-grid',
	//'template' => '{items} {pager}',
	'summaryText' => 'Showing {start} to {end} admins of {count}. Page {page} of {pages}',
	'enableSorting' => false,
	'rowHtmlOptionsExpression'=>'array(
		"id" => "admin_$data->id",
		"style" => "cursor:pointer;",
		"class" => "admintr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
	'columns'=>array(
		array(
			'name' => 'username',
			'value' => '$data->username',
		),
		array(
			'name' => 'access',
			'value' => 'Amxadmins::GetMyFlags($data->access)',
		),
		array(
			'name' => 'expires',
			'type' => 'raw',
			'value' => '$data->expires == 0 ? "<i>Never</i>" : Amxadmins::GetDays($data->expires)',

		),
	),
)); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal',
	array(
		'id'=>'adminDetail',
		'htmlOptions' => array(
			'style' => 'width: 600px; margin-left: -300px; min-height: 400px'
		)
)); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal" rel="tooltip" data-placement="left" title="Close">&times;</a>
    <h4>Admin Details</h4>
</div>
<div class="modal-body" style="min-height: 350px">
	<h3>Info</h3>
	<div id="adminInfo"></div>
	<hr>
	<h3>STEAM</h3>
	<div id="adminSteam"></div>
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

<div style="width: 200px; margin: 0 auto; text-align: center">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Access Information',
        'url'=>'#',
        'htmlOptions'=>array(
			'onclick'=>'$("#info_access").slideToggle("fast"); return false;',
		),
    )); ?>
</div>

<div id="info_access" class="row-fluid" style="display: none">
	<div class="span6">
		<h3 class="muted">Access Rights</h3>
		<?php
		foreach(Amxadmins::GetMyFlags( 'z', TRUE ) as $flag => $desc):
			if( $flag == 'z')
				continue;
			echo $desc . '<br />';
		endforeach;
		?>
	</div>
	<div class="span6">
		<h3 class="muted">Access Flags</h3>
		<?php
		foreach(Amxadmins::GetMyFlags( 'z', TRUE ) as $flag => $desc):
			if( $flag == 'z')
				continue;
			echo $flag . '<br />';
		endforeach;
		?>
	</div>
</div>