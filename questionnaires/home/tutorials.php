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
					<a href="https://youtu.be/bATV_alIUN0" target="_blank">
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
					<a href="https://docs.google.com/presentation/d/1ZJauWajBo2KsntpV-tpZN7tUy16PAdDOs_U2v_fnHcw" target="_blank">
					<!-- <a class="aFile" file="Siap Tanggap Panduan Pengguna  (One-Pager).pdf" target="_blank"> -->
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
		<!-- <div class="col-md-6">
			<div class="row" style="padding-top: 10px;">
				<div class="col-4" style="padding-right: 0px;">
					<a href="https://docs.google.com/forms/d/e/1FAIpQLSfjwMNX__6XQx9yS4x5rtq9bYOTaZPYjcND7jVDk3ucNYOZYw/viewform" target="_blank">
						<img class="imgTut" file="tutorial2.png" width="100%" />
					</a>
				</div>
				<div class="col-8">
					<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
						<strong><?php //echo TR('tut2Name'); ?></strong><br/>
						<span><?php //echo TR('tut2Descript'); ?></span>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>