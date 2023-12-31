<?php
/**
 * Вьюшка главной страницы банов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$page = 'Bans';
$this->pageTitle = Yii::app()->name . ' - ' . $page;

$this->breadcrumbs=array(
	$page,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle(\"fast\");
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bans-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
Yii::app()->clientScript->registerScript('banlist', "
$('.bantr').on('click', function(){
	$('#loading').show();
	var bid = this.id.substr(4);
	$.post('".Yii::app()->createUrl('bans/bandetail/')."', {'bid': bid}, function(data){
		eval(data);
	});
})
");



?>

<div class="alert alert-<?php echo $check ? 'error' : 'success' ?>">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<?php 
	$ip = $_SERVER['REMOTE_ADDR'];
	echo $check
			?
		'<strong>Attention!</strong> Your IP (<strong>'.$ip.'</strong>) is banned'
			:
		'Your IP (<strong>'.$ip.'</strong>) is not in the ban list!'
	?>
</div>

<?php echo CHtml::link('Search','#',array('class'=>'search-button btn btn-small')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div>
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	'id'=>'bans-grid',
    'dataProvider'=>isset($_GET['Bans']) ? $model->search() : $dataProvider,
	//'template' => '{items} {pager}',
	'summaryText' => 'Displaying {start} to {end} bans from {count}. Page {page} of {pages}',
	'htmlOptions' => array(
		'style' => 'width: 100%'
	),
	'rowHtmlOptionsExpression'=>'array(
		"id" => "ban_$data->bid",
		"style" => "cursor:pointer;",
		"class" => $data->getUnbanned()? "bantr success" : "bantr"
	)',
	'pager' => array(
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast' => true,
	),
    'columns'=>array(

		array(
			'header' => 'Date',
			'value'=>'date("d.m.Y H:i", $data->ban_created)',
			'htmlOptions' => array('style' => 'width:100px'),
		),
		array(
			'header' => 'Player Nick',
			'type' => 'raw',
			'value' => '$data->country . " " . CHtml::encode(mb_substr($data->player_nick, 0, 18, "UTF-8"))',
			'htmlOptions' => array(
				'style' => 'width: 180px'
			)
		),

		array(
			'header' => 'Admin',
			'value' => '$data->admin_nick',
		),

		array(
			'header' => 'Reason',
			'value' => 'mb_strlen($data->ban_reason, "UTF-8") > 25 ? mb_substr($data->ban_reason, 0, 25, "UTF-8") . "..." : $data->ban_reason'
		),

		array(
			'header' => 'Length',
			'value' => '$data->ban_length == \'-1\' ? \'Unbanned\' : Prefs::date2word($data->ban_length) . ($data->getUnbanned() ? \' (Expired)\' : \'\')',
			'htmlOptions' => array('style' => 'width:130px'),
		),

		array(
			'header' => 'Kicks',
			'value' => '$data->ban_kicks',
			'htmlOptions' => array('style'=>'text-align: center'),
			'visible' => Yii::app()->config->show_kick_count,
		) ,
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header' => '',
			'template'=>'{view}',
		)
	),
));
?>

<?php $this->beginWidget('bootstrap.widgets.TbModal',
	array(
		'id'=>'BanDetail',
		'htmlOptions'=> array('style'=>' width: 600px; margin-left: -300px'),
)); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Ban Details </h4>
</div>

<div class="modal-body" id="ban_name">
<table class="items table table-bordered table-condensed" style="width:500px; margin: 0 auto">
	<tr class="odd">
		<td class="span3">
			<b>Nick</b>
		</td>
		<td class="span6" id="bandetail-nick">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>SteamID</b>
		</td>
		<td id="bandetail-steam">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Steam Community</b>
		</td>
		<td id="bandetail-steamcommynity">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>IP</b>
		</td>
		<td id="bandetail-ip">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Ban Type</b>
		</td>
		<td id="bandetail-type">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Reason</b>
		</td>
		<td id="bandetail-reason">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Date</b>
		</td>
		<td id="bandetail-datetime">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Expired</b>
		</td>
		<td id="bandetail-expired">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Admin</b>
		</td>
		<td id="bandetail-admin">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Server</b>
		</td>
		<td id="bandetail-server">
		</td>
	</tr>
	<tr class="odd">
		<td>
			<b>Kicks</b>
		</td>
		<td id="bandetail-kicks">
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'label'=>'Show Details',
				'url'=> '#',
				'htmlOptions'=>array('id' => 'viewban'),
			)); ?>
		</td>
	</tr>
</table>
<br>

</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>