<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="application/xhtml+xml;charset=ISO-8859-1" http-equiv="Content-Type" />
		<script type="text/javascript" src="<?php echo $_SESSION['config']->getPathApp(); 
				?>/view/js/functions.js"></script>
		<script type="text/javascript" src="<?php echo $_SESSION['config']->getPathApp(); 
				?>/view/js/ajax.js"></script>
		<title>Contactos - <?php echo APP_NAME; ?></title>
	</head>
	<body>
		<div>
			<?php include $_SESSION['config']->getPathView() . '/includes/menu.php'; ?>
		</div>
		<h1>Contactos</h1>
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Correo electr&oacute;nico</th>
					<th>Operaciones</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($usuario->contactos as $contacto) { ?>
					<tr id="contacto_<?php echo $contacto->id_usuario; ?>">
						<td><?php echo htmlentities($contacto->nombre); ?></td>
						<td><?php echo htmlentities($contacto->email); ?></td>
						<td><?php olink('borrar_contacto', 'contacto_' . $contacto->id_usuario
							, array('id_usuario' => $contacto->id_usuario), true
							, '¿Deseas eliminar este contacto?'); 
							?><span style="color: red;">Eliminar</span><?php clink(); ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>