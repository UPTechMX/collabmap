<script type="text/javascript">
	$(document).ready(function() {
		$.each($('.imgTut'), function(index, val) {
			var file = $(this).attr('file');
			$(this).attr({src:rz+'img/'+file});
		});
		$.each($('.aFile'), function(index, val) {
			var file = $(this).attr('file');
			$(this).attr({href:rz+'img/'+file});
		});
	});
</script>

<div style="margin-top: 10px; color: grey;">
	<div class="nuevo"><?php echo TR('tutorials'); ?></div>
	<div class="row" style="border:solid 0px;">
		<div class="col-md-6" >
			<div class="row" style="padding-top: 10px;">
				<div class="col-4" style="padding-right: 0px;">
					<a href="https://www.youtube.com/watch?v=Ro-aHLC6N2o" target="_blank">
						<img class="imgTut" file="tutorial0.png" width="100%" />
					</a>
				</div>
				<div class="col-8">
					<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
						<strong><?php echo TR('vid1Name'); ?></strong><br/>
						<span><?php echo TR('vid1Descript'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row" style="padding-top: 10px;">
				<div class="col-4" style="padding-right: 0px;">
					<a class="aFile" file="Siap Tanggap Panduan Pengguna  (One-Pager).pdf" target="_blank">
						<img class="imgTut" file="tutorial1.png" width="100%" />
					</a>
				</div>
				<div class="col-8">
					<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
						<strong><?php echo TR('tut1Name'); ?></strong><br/>
						<span><?php echo TR('tut1Descript'); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>