<?php

// echo "$lang<br/>";
$langs = ['en','es','id'];
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.lang').click(function(event) {
			var lang = this.id.split('_')[1];
			
		});
	});
</script>
<div style=" text-align: right;margin-top: 8px;font-size: small;">
	<?php 
	$i = 0;
	foreach ($langs as $l){
		if(strpos($_SERVER['QUERY_STRING'], 'lang') !== false){
			$pos = strpos($_SERVER['QUERY_STRING'], 'lang');
			$query = substr($_SERVER['QUERY_STRING'], 0,$pos-1);
			$query .= substr($_SERVER['QUERY_STRING'], $pos+7);
		}else{
			$query = $_SERVER['QUERY_STRING'];
		}
		// echo $query;
		
	?>
		<?php if ($i != 0){ ?>
			|
		<?php } ?>
		<a href="<?php echo "$_SERVER[PHP_SELF]?$query&lang=$l"; ?>" style="text-decoration: none;">
			<span class="manita lang" id="lang_<?php echo $l; ?>" style="color:<?php echo strpos($lang, $l) !== false?'orange':grey; ?>">
				<?php echo strtoupper($l) ?>
			</span>
		</a>
	<?php $i++; } ?>	
</div>
