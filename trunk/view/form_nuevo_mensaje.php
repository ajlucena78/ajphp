<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Nuevo mensaje - <?php echo APP_NAME; ?></title>
		<?php include $_SESSION['config']->getPathView() . '/includes/head.php'; ?>
		<script type="text/javascript" src="<?php echo $_SESSION['config']->getPathApp(); 
				?>/view/js/ajax.js"></script>
	</head>
	<body>
		<div>
			<?php include $_SESSION['config']->getPathView() . '/includes/menu.php'; ?>
		</div>
		<h1>Nuevo mensaje</h1>
		<form action="<?php vlink('envia_mensaje'); ?>" method="post">
			<?php if ($error) { ?>
				<div class="error"><?php echo htmlspecialchars($error); ?></div>
			<?php } ?>
			<div>
				<?php if (isset($errores['usuario_destino'])) { ?>
					<div class="error"><?php echo htmlspecialchars($errores['usuario_destino']); ?></div>
				<?php } ?>
				<label for="usuario_destino">Destino:</label>
				<br />
				<select name="usuario_destino" id="usuario_destino">
					<option></option>
					<?php foreach ($usuario->contactos as $contacto) { ?>
						<option value="<?php echo $contacto->id_usuario; ?>"<?php 
								if ($mensaje->usuario_destino 
										and $contacto->id_usuario == $mensaje->usuario_destino->id_usuario) { 
								?> selected="selected"<?php } ?>>
							<?php echo $contacto->nombre; ?> (<?php echo $contacto->email; ?>)
						</option>
					<?php } ?>
				</select>
			</div>
			<div>
				<?php if (isset($errores['mensaje'])) { ?>
					<div class="error"><?php echo htmlspecialchars($errores['mensaje']); ?></div>
				<?php } ?>
				<label for="mensaje">Mensaje:</label>
				<br />
				<textarea name="mensaje" id="mensaje" rows="6" cols="40"><?php 
						echo htmlentities($mensaje->mensaje); ?></textarea>
			</div>
			<div>
				<input type="submit" value="Enviar" />
			</div>
		</form>
	</body>
</html>