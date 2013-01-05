<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Mensaje enviado - <?php echo APP_NAME; ?></title>
		<?php include $_SESSION['config']->getPathView() . '/includes/head.php'; ?>
	</head>
	<body>
		<div>
			<?php include $_SESSION['config']->getPathView() . '/includes/menu.php'; ?>
		</div>
		<h1>Mensaje enviado</h1>
		<div>
			Tu mensaje ha sido enviado correctamente a <?php 
					echo htmlentities($mensaje->usuario_destino->nombre); ?>.
			<br />
			<a href="<?php vlink('mensajes'); ?>">Volver a mensajes</a>
		</div>
	</body>
</html>