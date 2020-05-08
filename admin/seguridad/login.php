<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Admin Login</title>
	<!-- LIBRERIAS CSS -->
	<link href="<?php echo aRaiz(); ?>lib/js/bootstrap4/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>lib/css/general.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaiz(); ?>seguridad/loginCSS.css" rel="stylesheet" type="text/css" />
	
	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="<?php echo aRaiz(); ?>lib/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo aRaiz(); ?>lib/js/bootstrap4/js/bootstrap.min.js"></script>

</head>

<body>

	<div class="container">
		<div class="header">
		</div>		
		<?php include aRaiz().'general/lang.php'; ?>
		<img src="<?php echo aRaiz(); ?>img/marquesina.png"  style="width:100%;" >
		<hr />
		<div class="content" style="margin:5%">
			<div class="row">
				<div class="col-lg-8 col-md-4 col-sm-1" style="padding:35px 10px 20px 10px;">
				</div>
				<div class="col-lg-4 col-md-8 col-sm-11">
					<form id="form1" name="form1" method="post" action="<?php $_SERVER['PHP_SELF'] ?>?">
						<div align="center" style="border:solid #00aeef;padding:20px 10px 20px 10px;
							background:#fff;width:100%;border-radius:10px;color:black;">
							<table>
								<tr>
									<td><?php echo TR('username'); ?>: </td>
									<td>&nbsp;&nbsp;</td>
									<td><input type="text" name="usuario" id="usuario" class="form-control" style="border-radius:0px;" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;&nbsp;</td>
									<td></td>
								</tr>
								<tr>
									<td><?php echo TR('password'); ?>: </td>
									<td>&nbsp;&nbsp;</td>
									<td><input type="password" name="pwd" id="pwd"  class="form-control" style="border-radius:0px;"/></td>
								</tr>
							</table>
							<br/>
							<br/>
							<input type="submit" name="button" id="button" value="<?php echo TR('log_in'); ?>" class="btn btn-shop" />
						</div>
					</form>
				</div>
			</div>

		</div>
		<div class="footer"><?php include aRaiz().'admin/layout/footer.php'; ?></div>

	</div>
</body>
</html>