<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$padre = empty($_POST['padre']) ? 0 : $_POST['padre'];
$nivelMax = empty($_POST['nivelMax']) ? 0 : $_POST['nivelMax'];

$LJ = getLJTrgt($nivelMax,$padre,$_POST['elemId'],'documents');
// print2($LJ);

$lastLevel = intval($LJ['numDim'])-1;
// echo "AAAAAA: $lastLevel";

$sql = "SELECT COUNT(*) as cuenta $LJ[fields]
	FROM DocumentsComments te
	$LJ[LJ]
	WHERE documentsId = $_POST[elemId] AND $LJ[wDE]
	GROUP BY de$lastLevel.id";

// print2($sql);
$comments = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// print2($comments);
?>


<script type="text/javascript">
	$(document).ready(function() {

		var comments = <?php echo atj($comments); ?>;
		var data = [];
		for(var i = 0;i<comments.length;i++){
			var tmp = {};
			tmp['name'] = comments[i]['nombreHijo'];
			tmp['y'] = comments[i]['cuenta'];
			data.push(tmp);
		}

		// console.log(data);

		Highcharts.chart('containerDoc', {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: '<?php echo TR('comments'); ?>'
		    },
		    tooltip: {
		    	pointFormat: '{series.name}: <b>{point.y}</b><br/>'+
		    		'<?php echo TR('percent'); ?>: <b>{point.percentage:.1f}%</b>'
		    },
		    accessibility: {
		        point: {
		            valueSuffix: '%'
		        }
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: false
		            },
		            showInLegend: true
		        }
		    },
		    series: [{
		        name: 'Comments',
		        colorByPoint: true,
		        data: data
		    }]
		});
	});
</script>

<div>
	<div class="row">
		<div class="col-4">
			<table class='table'>
				<thead>
					<tr>
						<th><?php echo TR('docLevel'); ?></th>
						<th style="text-align: center;"><?php echo TR('count'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($comments as $c){ ?>
						<tr>
							<td><?php echo $c['nombreHijo']; ?></td>
							<td style="text-align: center;"><?php echo $c['cuenta']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="col-8">
    		<div id="containerDoc"></div>
		</div>
	</div>
</div>

