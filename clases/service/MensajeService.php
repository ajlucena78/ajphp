<?php
	class MensajeService extends Service
	{
		public function valida(Mensaje $mensaje)
		{
			$errores = array();
			if (!$mensaje->id_mensaje)
				$errores['id_mensaje'] = 'Es necesario indicar el identificador del mensaje';
			if (!$mensaje->usuario_envia or !$mensaje->usuario_envia->id_usuario)
				$errores['usuario_envia'] = 'Es necesario indicar el usuario que envia el mensaje';
			if (!$mensaje->usuario_destino or !$mensaje->usuario_destino->id_usuario)
				$errores['usuario_destino'] = 'Es necesario indicar el usuario que recibe el mensaje';
			if (!$mensaje->mensaje)
				$errores['mensaje'] = 'Es necesario indicar el texto del mensaje';
			if (!$mensaje->fecha_envio)
				$errores['fecha_envio'] = 'Es necesario indicar la fecha de envío del mensaje';
			if (count($errores))
				return $errores;
			return true;
		}
	}