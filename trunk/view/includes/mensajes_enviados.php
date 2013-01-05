<h2>Mensajes enviados</h2>
<?php if (!count($mensajes)) { ?>
	<div>No has enviado ning&uacute;n mensaje</div>
<?php }else{ ?>
	<table>
		<thead>
			<tr>
				<th>Destinatario</th>
				<th>Fecha</th>
				<th>Mensaje</th>
				<th>Operaciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($mensajes as $mensaje) { ?>
				<tr>
					<td><?php echo htmlentities($mensaje->usuario_destino->nombre); ?></td>
					<td><?php echo $mensaje->fecha_envio; ?></td>
					<td><?php echo htmlentities($mensaje->mensaje); ?></td>
					<td><?php olink('borrar_mensaje', 'mensajes', array('id_mensaje' => $mensaje->id_mensaje)
							, null, '¿Deseas borrar este mensaje?'); 
							?><span style="color: red;">Eliminar</span><?php clink(); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>