<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Inicio de sesi&oacute;n - <?php echo APPNAME; ?></title>
		<?php include $_SESSION['config']->getPathView() . '/includes/head.php'; ?>
	</head>
	<body>
		<h1>Inicio de sesi&oacute;n</h1>
		<?php if ($error) { ?>
			<div style="color: red;">
				Error: <?php echo $error; ?>
			</div>
		<?php } ?>
		<form action="<?php vlink('login'); ?>" method="post">
			<div>
				<label for="email">Correo electr&oacute;nico:</label> 
				<input name="email" id="email" type="text" value="<?php echo $usuario->email; ?>" />
			</div>
			<div>
				<label for="clave">Contrase&ntilde;a:</label> 
				<input name="clave" id="clave" type="password" value="" />
			</div>
			<div>
				<input type="submit" value="Entrar" />
			</div>
		</form>
		<script type="text/javascript">
			document.getElementById('email').focus();
		</script>
	</body>
</html>