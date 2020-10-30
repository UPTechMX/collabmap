<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$cPoll = $db -> query("SELECT c.id, c.poll
	FROM Consultations c 
	LEFT JOIN Projects p ON p.id = c.projectsId
	WHERE c.id = $_POST[consultationId]")->fetchAll(PDO::FETCH_ASSOC)[0];

$scores = $db->query("SELECT COUNT(*) as cuenta, score
	FROM UsersQuickPoll 
	WHERE consultationsId = $_POST[consultationId]
	GROUP BY score
	ORDER BY score")->fetchAll(PDO::FETCH_ASSOC);

// print2($scores);

?>


<div class="nuevo grisBkg"><?php echo TR('quickvote'); ?></div>

<div style="padding: 10px 0px;">
	<?php if (empty($cPoll['poll'])){ ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#pollText").jqte({
					source:true,
					rule: false,
					link:false,
					unlink: false,
					format:false
				});

				$("#savePoll").click(function(event) {
					var dat = {};
					dat.poll = $('#pollText').val();
					dat.id = '<?php echo $_POST["consultationId"]; ?>';
					var rj = jsonF('admin/administration/consultations/json/json.php',{opt:1,acc:2,datos:dat});
					console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('#consultationsPoll').load(rz+'admin/administration/consultations/consultationsPoll.php',{consultationId:dat.id})
					}


				});
			});
		</script>
		<textarea class="form-control" id="pollText"></textarea>
		<div style="text-align: right;margin-top: 10px;">
			<span class="btn btn-shop" id="savePoll"><?php echo TR('send'); ?></span>
		</div>
	<?php }else{ ?>
		<script type="text/javascript">
			$(document).ready(function() {
				var scores = <?php echo atj($scores); ?>;
				var data = [];
				for(var i = 0;i<scores.length;i++){
					var tmp = {};
					tmp['name'] = ''+scores[i]['score'];
					tmp['y'] = scores[i]['cuenta'];
					data.push(tmp);
				}

				Highcharts.chart('container', {
				    chart: {
				        plotBackgroundColor: null,
				        plotBorderWidth: null,
				        plotShadow: false,
				        type: 'pie'
				    },
				    title: {
				        text: '<?php echo TR('score'); ?>'
				    },
				    tooltip: {
				    	enabled:false,
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
				                enabled: true
				            },
				            showInLegend: true
				        }
				    },
				    series: [{
				        name: 'Score',
				        colorByPoint: true,
				        data: data
				    }]
				});


			});
		</script>
		<div>
			<?php echo $cPoll['poll']; ?>
		</div>
		<div style="margin-top: 10px;">
			<div id="container"></div>
		</div>
		<div>
			<table class="table">
				<thead>
					<tr>
						<th><?php echo TR('score'); ?></th>
						<th style="text-align: center;"><?php echo TR('ansNumber'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sum = 0;
					$cuenta = 0;
					foreach ($scores as $s){ 
						$sum += $s['score']*$s['cuenta'];
						$cuenta += $s['cuenta'];
					?>
						<tr>
							<th><?php echo $s['score']; ?></th>
							<td style="text-align: center;"><?php echo $s['cuenta']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total:</th>
						<th style="text-align: center;"><?php echo empty($cuenta)?0:number_format($sum/$cuenta,2); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php } ?>
</div>