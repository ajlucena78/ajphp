<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Mensajes - <?php echo APP_NAME; ?></title>
		<?php include $_SESSION['config']->getPathView() . '/includes/head.php'; ?>
		<script type="text/javascript" src="<?php echo $_SESSION['config']->getPathApp(); 
				?>/view/js/functions.js"></script>
		<script type="text/javascript" src="<?php echo $_SESSION['config']->getPathApp(); 
				?>/view/js/ajax.js"></script>
	</head>
	<body>
		<div>
			<?php include $_SESSION['config']->getPathView() . '/includes/menu.php'; ?>
		</div>
		<h1>Mensajes</h1>
		<?php olink('mensajes_recibidos', 'mensajes'); ?>Mensajes recibidos<?php clink(); ?>
		|
		<?php olink('mensajes_enviados', 'mensajes'); ?>Mensajes enviados<?php clink(); ?>
		|
		<a href="<?php vlink('nuevo_mensaje'); ?>">Nuevo mensaje</a>
		<div id="mensajes">
			<?php carga('mensajes_recibidos', 'mensajes'); ?>
		</div>
	</body>
</html>