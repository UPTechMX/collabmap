<script type="text/javascript">
	$(document).ready(function() {
		$.each($('.imgTut'), function(index, val) {
			var file = $(this).attr('file');
			$(this).attr({src:rz+'img/'+file});
		});
	});
</script>

<div style="margin-top: 10px; color: grey;">
	<div class="nuevo"><?php echo TR('tutorials'); ?></div>
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-4">
					<a href="https://www.youtube.com/watch?v=Ro-aHLC6N2o" target="_blank">
						<img class="imgTut" file="tutorial0.png" height="100px" />
					</a>
				</div>
				<div class="col-8">
					<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
						<strong>Panduan Pengguna Semarang SIAP</strong><br/>
						<span>CAPSUS Education</span>

					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-4">
					<a href="https://docs.google.com/presentation/d/1kLhHbgRvONqshZgrydCCgFcJY31g51ej5mTXVHU4LLc" target="_blank">
						<img class="imgTut" file="tutorial1.png" height="100px" />
					</a>
				</div>
				<div class="col-8">
					<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
						<strong>Instruction guide</strong><br/>
						<span>Follow these 5 steps to get started with Semarang Siap</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>