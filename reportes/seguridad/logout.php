<?php if (privilegio() >= 1): ?>
<?php $name = $_SESSION['nombre'];?>

    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout=1" title="#" class="btnC logout style1">Cerrar Sesión</a> 
     - - - - - - - - - - - - 
<?php endif; ?>