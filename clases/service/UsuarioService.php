<?php
	class UsuarioService extends Service
	{
		public function login(Usuario & $usuario)
		{
			$sql = 'select * from Usuario';
			$sql .= ' where email = \'' . str_replace('\'', '', $usuario->email) . '\'';
			$sql .= ' and clave = \'' . str_replace('\'', '', $usuario->clave) . '\'';
			$consulta = new Consulta(parent::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->get_error();
				return false;
			}
			$res = true;
			if ($registro = $consulta->lee_registro())
				$usuario = new Usuario($registro);
			else
				$res = null;
			$consulta->libera();
			return $res;
		}
		
		public function check_usuario($usuario, $sessionId)
		{
			$res = false;
			if (isset($usuario) and is_object($usuario) and isset($sessionId) and $sessionId)
			{
				if (md5($usuario->email) == $sessionId)
					$res = true;
			}
			return $res;
		}
	}