<script type="text/javascript">
	$(document).ready(function() {
		<?php if ($spatial['tsiglas'] == 'cm'){ ?>
			
			$("#btnCat_<?php echo $spatial['id'];?>").click(function(event) {
				var dat = {};
				var catName = $("#catNom_<?php echo $spatial['id'];?>").val();
				dat.name = catName;
				dat.preguntasId = <?php echo $spatial['id'];?>;
				
				var rj = jsonF('admin/checklist/json/json.php',{acc:1,datos:dat,opt:12});
				// console.log(rj);
				var r = $.parseJSON(rj);

				if(r.ok == 1){
					$("#catList_<?php echo $spatial['id'];?>").load(rz+'admin/checklist/catList.php',{pId:<?php echo $spatial['id']; ?>});
					$("#catNom_<?php echo $spatial['id'];?>").val('').focus();
				}

			});

			$('#catList_<?php echo $spatial['id']; ?>').on('click', '.delCat', function(event) {
				event.preventDefault();
				
				var catId = this.id.split('_')[1];
				var rj = jsonF('admin/checklist/json/json.php',{acc:11,catId:catId});

				var r = $.parseJSON(rj);

				if(r.ok == 1){
					$(this).closest('tr').remove();
				}

			});


		<?php } ?>

	});
</script>