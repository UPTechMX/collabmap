<?php

	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	$usrId = $_SESSION['CM']['consultations']['usrId'];

	$news = $db->query("SELECT n.*,ul.newsId 
		FROM News n 
		LEFT JOIN UsersLikes ul ON ul.newsId = n.id AND usersId = '$usrId'
		ORDER BY n.timestamp DESC
	")->fetchAll(PDO::FETCH_ASSOC);

?>


<script type="text/javascript">
	$(document).ready(function() {
		$('.readMore').click(function(event) {
			var newsId = $(this).closest('.divNews').attr('id').split('_')[1];
			popUp('consultations/news/news.php',{newsId:newsId});
		});

		$('.like').click(function(event) {
			
			<?php if (!empty($usrId)){ ?>
				var divCont = $(this).closest('.extLikes');
				var count = parseInt(divCont.find('.count').text());
				var nId = divCont.closest('.divNews').attr('id').split('_')[1];

				var rj = jsonF('consultations/news/json/json.php',{acc:1,newsId:nId});
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					$(this).removeClass('noLiked');
					$(this).addClass('liked');
					count++;
				}else if(r.ok == 2){
					$(this).removeClass('liked');
					$(this).addClass('noLiked');
					count--;
				}
				divCont.find('.count').text(count);

			<?php }else{ ?>
				alerta('success','<?php echo TR("needLogin"); ?>');
			<?php } ?>


		});
	});
</script>
<div style="color:#2a6bd5; text-align: left;">
	<div class="consultationName azul" style="font-size: 2em;font-weight: bold;text-align: left;">
		<?php echo TR('publicNews'); ?>
	</div>
	<div style="position: relative; margin-bottom: 30px; margin-top: 30px;" >
		<hr>
		<div style="background-color: #CCC;width: 10px;height: 10px;border-radius: 50%;position: absolute;top:-4px;"></div>
		<div style="background-color: #CCC;width: 10px;height: 10px;border-radius: 50%;position: absolute;top:-4px;left: 20px;"></div>
	</div>

	<div style="margin-top: 20px;">
		<?php 
			foreach ($news as $n){ 
				// print2($n);
			$count = $db->query("SELECT COUNT(*) as cuenta FROM UsersLikes WHERE newsId = $n[id]")->fetchAll(PDO::FETCH_NUM)[0][0];
		?>
			<div class="divNews" id="divNews_<?php echo $n['id']; ?>">
				<div class="extNewsName azul"><?php echo $n['name']; ?></div>
				<div class="extNewsHeade negro"><?php echo $n['header']; ?></div>
				<div style="margin-top: 10px;">
					<span class="sidebarElement readMore azul" style="font-size: .9em;" >
						<i class="glyphicon glyphicon-forward"></i><?php echo TR('iWantMore') ?>
					</span>
				</div>
				<br/>
				<!-- <div class="extLikes" style="margin-top: 5px;">
					<span">
						<?php
							$class = empty($n['newsId'])?'noLiked':'liked';
						?>
						<i class="fas fa-thumbs-up manita like <?php echo $class; ?>"></i>
					</span>&nbsp;&nbsp;
					<strong class="count rojo"><?php echo $count; ?></strong> 
				</div> -->
			</div>
		<?php } ?>
	</div>
</div>