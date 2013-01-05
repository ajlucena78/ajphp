<h2>Mensajes recibidos</h2>
<?php if (!count($mensajes)) { ?>
	<div>No hay mensajes recibidos</div>
<?php }else{ ?>
	<table>
		<thead>
			<tr>
				<th>Remitente</th>
				<th>Fecha</th>
				<th>Mensaje</th>
				<th>Operaciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($mensajes as $mensaje) { ?>
				<tr>
					<td><?php echo htmlentities($mensaje->usuario_envia->nombre); ?></td>
					<td><?php echo $mensaje->fecha_envio; ?></td>
					<td><?php echo htmlentities($mensaje->mensaje); ?></td>
					<td><?php olink('borrar_mensaje', 'mensajes', array('id_mensaje' => $mensaje->id_mensaje)); 
							?><span style="color: red;">Eliminar</span><?php clink(); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>