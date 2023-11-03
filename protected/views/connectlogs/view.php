<?php

$page = 'Connect Logs';
$this->pageTitle = Yii::app()->name . ' - ' . $page . ' - Player Details ' . $model->nick;
$this->breadcrumbs=array(
	$page=>array('index'),
	$model->nick,
);

$steam = '';
//;
if( !empty($model->steamid) && preg_match("/^STEAM_0:[0-1]:[0-9]{1,15}$/",$model->steamid))
{
	if($url = @file_get_contents(Prefs::steam_convert($model->steamid, false, true)))
	{
		$xmlres = simplexml_load_string($url);
		$steam = CHtml::image($xmlres->avatarIcon) . " " . CHtml::link(
			$xmlres->steamID . " | ".$model->steamid,
			"http://steamcommunity.com/profiles/" . $xmlres->steamID64,
			array(
				"target" => "_blank",
				"rel" => "tooltip",
				"title" => "View profile"
			)
		);
	}
}

?>

<h2>Player Details <i><?php echo CHtml::encode($model->nick); ?></i></h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'type' => array( 'bordered'),
	'htmlOptions' => array('style'=>'text-align: left'),
	'attributes'=>array(
		array(
			'name' => 'Last Connect',
			'type' => 'raw',
			'value' => date('d.m.Y - H:i:s', $model->date),
			'htmlOptions' => array('style'=>'height: 200px'),
		),
		array(
			'name' => 'steamid',
			'type' => 'raw',
			'value' => $steam? $steam : $model->steamid,
		),
		'nick',
		'ip',
		array(
			'name' => 'played_time',
			'type' => 'raw',
			'value' => Prefs::date2word($model->played_time, false, true)
		)
		
	),
)); ?>

<hr>
<p class="text-success">
	<i class="icon-calendar"></i>
	Player History (Different Nicks, IPs, SteamIDs)
</p>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'type' => 'bordered stripped',
	'id'=>'connectlogs-history-grid',
	'dataProvider'=>$history,
	'enableSorting' => FALSE,
	'template' => '{items} {pager}',
	'columns'=>array(
		array(
			'name' => 'nick',
			'type' => 'raw',
			'value' => 'CHtml::encode($data->nick)'
		),
		array(
			'name' => 'steamid',
			'type' => 'raw',
			'value' => 'Prefs::steam_convert($data->steamid, TRUE)
				? CHtml::link($data->steamid, "http://steamcommunity.com/profiles/"
						. Prefs::steam_convert($data->steamid), array("target" => "_blank"))
				: $data->steamid',
		),
		array(
			'name' => 'ip',
			'value' => '$data->ip',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header' => '',
			'template'=>'{view}',
			'visible'=>Webadmins::checkAccess('cl_view')
		),
	),

));
?>

