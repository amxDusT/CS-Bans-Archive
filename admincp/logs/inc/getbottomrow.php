<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
    die();
}

require_once 'app.php';


// if (!isset($_GET['id'])) {
// 	$_GET['id'] = date("Y-m-d", strtotime('-7 day')).",".date("Y-m-d");
// }

$database->query('SELECT `country` AS label, COUNT(*) AS value FROM `'.DB_TABLE_PA.'` '.getIpDatesSql($include_where = true).' GROUP BY `country`'); #count(*) instead of count(country) bcs we need to count also NULL values 
$country = $database->resultset();

$database->query('SELECT `premium` AS label, COUNT(*) AS value FROM `'.DB_TABLE_PA.'` '.getIpDatesSql($include_where = true).' GROUP BY `premium`');
$premium = $database->resultset();


$country = processCountries($country, $Show_Max_Countries);
$premium = processPremium($premium);

$country = json_encode($country);
$premium = json_encode($premium);
?>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i> Top <?php echo $Show_Max_Countries ?> Countries
							</div>
							<div class="panel-body">
								<div id="country"></div>
								<a href="#/stats/locations" class="btn btn-default btn-block"><input type="hidden" value="getlocation"/>View Details</a>

							</div><!-- /.panel-body -->
						</div><!-- /.panel -->
					</div><!-- /.col-lg-4 -->
					<!-- <div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i> Connection Method
							</div>
							<div class="panel-body">
								<div id="method"></div>
								<a href="#/stats/connections" class="btn btn-default btn-block"><input type="hidden" value="getconnections"/>View Details</a>
							</div>// /.panel-body //
						</div>// /.panel //
					</div>// /.col-lg-4 //
					-->
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i> Premium
							</div>
							<div class="panel-body">
								<div id="premium"></div>
								<a href="#/stats/players" class="btn btn-default btn-block"><input type="hidden" value="getplayers"/>View Details</a>
							</div><!-- /.panel-body -->
						</div><!-- /.panel -->
					</div><!-- /.col-lg-4 -->
<script type="text/javascript">
	Morris.Donut({
	  element: 'country',
	  data: <?php echo $country; ?>,
	  pointFillColors: ['#ffffff'],
	  formatter: function (y) { return y + "%" ;}
	});
</script>
<script type="text/javascript">
	Morris.Donut({
	  element: 'premium',
	  data: <?php echo $premium; ?>,
	  formatter: function (y) { return y + "%" ;}
	});
</script>

<?php

function processCountries($country, $Show_Max_Countries) {

	if(empty($country)) {
		return NoChartData();
	}

	$c_total = 0;

	foreach ($country as $key => $value) {
		$c_total += $value['value'];
	}

	foreach ($country as $key => $value) {
		$country[$key]['value'] = number_format($value['value']/$c_total*100,2);
	}

	#show the top ten countries only
	$tmp_countries = $country;
	$countries_filtered = array();
	$c_percent = 0;
	$skip_other_country_calc = false;

	if(count($country) <= $Show_Max_Countries) {
		$Show_Max_Countries = count($country);
		$skip_other_country_calc = true;
	}


	if(count($country) > 1) {
		for($i = 1; $i <= $Show_Max_Countries; $i++) {
			$top = array_reduce($tmp_countries, function ($a, $b) {
				return @$a['value'] > $b['value'] ? $a : $b ;
			});
			foreach($tmp_countries as $key => &$c) {
				if($c['label'] == $top['label']) {
					unset($tmp_countries[$key]);
				}
			}

			$countries_filtered[] = $top;
			$c_percent += $top['value'];
		}

		$countries_filtered = array_reverse($countries_filtered); #reverse array order, so the donut is build up from big to small
		if(!$skip_other_country_calc) {
			$countries_filtered[] = array(
										'label' => 'Other Countries',
										'value' => number_format(100 - $c_percent,2),
			);
		}
		$country = $countries_filtered;
	}

	return $country;
}


function processPremium($premium) {
	if(empty($premium)) {
		return NoChartData();
	}

	$p_total = 0;

	foreach ($premium as $key => $value) {
		$p_total += $value['value'];
	}

	foreach ($premium as $key => $value) {
		if ($value['label'] == '1') {
			$value['label'] = 'VIP+';
		} 
		elseif ($value['label'] == '0') {
			$value['label'] = 'F2P';
		}
		else {
			$value['label'] = 'Unknown';
		}
		$premium[$key]['label'] = $value['label'];
		if(!$p_total == 0)
			$premium[$key]['value'] = number_format($value['value']/$p_total*100,2);
		else
			$premium[$key]['value'] = 0;
	}

	return $premium;
}

function NoChartData() {
	return array(
			0 => array(
				'label' => 'No Data',
				'value' => '0',
			)
		);
}
