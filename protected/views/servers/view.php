<?php
/**
 * Вьюшка просмотра деталей бана
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

$page = 'Servers';
$this->pageTitle = Yii::app()->name . ' - ' . $page . ' - Server Details ' . $model->hostname;
$this->breadcrumbs=array(
	$page=>array('index'),
	$model->hostname,
);
?>

<h2>Server Details <i><?php echo CHtml::encode($model->hostname); ?></i></h2>
<div style="float: right">
	<?php
	if(Webadmins::checkAccess('servers_start') && Webadmins::checkAccess('servers_stop')):
	echo CHtml::ajaxLink(
		'<i class="icon-refresh"></i>',
		$this->createUrl('/servers/sync', array('id' => $model->id)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {
				$(\'#loading\').show();
				if(!confirm("Restart '.$model->hostname.'?")) {$(\'#loading\').hide();return false;} 
			}',
			'complete' => 'function() {
				$(\'#loading\').hide();
			}',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/servers/index').'";}',
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Restart',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('servers_start')):
	echo CHtml::ajaxLink(
		'<i class="icon-play"></i>',
		$this->createUrl('/servers/start', array('id' => $model->id)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {
				$(\'#loading\').show();
				if(!confirm("Start '.$model->hostname.'?")) {$(\'#loading\').hide();return false;} 
			}',
			'complete' => 'function() {
				$(\'#loading\').hide();
			}',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/servers/index').'";}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Start',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('servers_stop')):
	echo CHtml::ajaxLink(
		'<i class="icon-stop"></i>',
		$this->createUrl('/servers/stop', array('id' => $model->id)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {
				$(\'#loading\').show();
				if(!confirm("Stop '.$model->hostname.'?")) {$(\'#loading\').hide();return false;} 
			}',
			'complete' => 'function() {
				$(\'#loading\').hide();
			}',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/servers/index').'";}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Stop',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('servers_edit')):
	echo CHtml::link(
		'<i class="icon-edit"></i>',
		$this->createUrl('/servers/update', array('id' => $model->id)),
		array(
			'rel' => 'tooltip',
			'title' => 'Edit',
		)
	);
	endif;
	?>
	&nbsp;
	<?php
	if(Webadmins::checkAccess('servers_edit')):
	echo CHtml::ajaxLink(
		'<i class="icon-remove"></i>',
		$this->createUrl('/servers/delete', array('id' => $model->id)),
		array(
			'type' => 'post',
			'beforeSend' => 'function() {if(!confirm("Remove '.$model->hostname.'?")) {return false;} }',
			'success' => 'function(data) {alert(data); document.location.href="'.$this->createUrl('/servers/index').'";}'
		),
		array(
			'rel' => 'tooltip',
			'title' => 'Remove',
		)
	);
	endif;
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'type' => array('condensed', 'bordered'),
	'htmlOptions' => array('style'=>'text-align: left'),
	'attributes'=>array(
		array(
			'name' => 'Server Name',
			'value' => $model->hostname
		),
		array(
			'name' => 'Server IP',
			'type' => 'raw',
			'value' => CHtml::link( $model->address.":".$model->port, $model->GetConnect() )
		),
		array(
			'name' => 'Map',
			'value' => $model->GetInfo()['map']
		),
		array(
			'name' => 'Nextmap',
			'value' => $model->GetInfo()['nextmap']
		),
		array(
			'name' => 'Timeleft',
			'value' => $model->GetInfo()['timeleft']
		),
		array(
			'name' => 'Players',
			'value' => $model->GetInfo()['players'] . " / " . $model->GetInfo()['maxplayers']
		),
	),
)); ?>

<hr>
<p class="text-success">
	<i class="icon-group"></i>
	Players
</p>

<div id="server-players-grid" class="grid-view">
	<table class="items table table-bordered">
		<thead>
			<tr>
				<th>Player Nick</th>
				<th>Score</th>
				<th>Time Playing</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$players = $model->GetInfo()['playersinfo'];
				$isOdd = true;
				foreach( $players as $player )
				{
					if( $isOdd == true )
						echo "<tr class=\"odd\">";
					else
						echo "<tr class=\"even\">";
					$isOdd = !$isOdd;
					echo "<td>" . $player['nick'] . "</td>";
					echo "<td>" . $player['score'] . "</td>";
					echo "<td>" . $player['playedtime'] . "</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
</div>
