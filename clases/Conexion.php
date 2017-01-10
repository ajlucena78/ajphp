<?php
	require_once('Consulta.php');
	
	class Conexion
	{
		private $conexion;
		private $error;
		
		private function msj_error()
		{
			$datos = $this->conexion->errorInfo();
			if (!isset($datos[2]))
				return null;
			return $datos[2];
		}
		
		public function conecta($dsn, $usuario, $clave)
		{
			try
			{
				$this->conexion = new PDO($dsn, $usuario, $clave);
				return true;
			}
			catch (PDOException $e)
			{
				$this->error = 'Fallo en la conexión ' . $dsn . ': ' . $e->getMessage();
				return false;
			}
		}
		
		public function desconecta()
		{
			$this->conexion = null;
		}
		
		public function ejecuta($sql)
		{
			$res = $this->conexion->exec($sql);
			if ($res === false)
				$this->error = 'Error en la ejecución de la consulta ' . $sql . ': ' . $this->msj_error();
			return $res;
		}
		
		public function carga_consulta($sql)
		{
			return $this->conexion->prepare($sql);
		}
		
		public function inicia_transaccion()
		{
			return $this->conexion->beginTransaction();
		}
		
		public function cierra_transaccion()
		{
			try
			{
				$this->conexion->commit();
			}
			catch (Exception $e)
			{
				$this->conexion->rollBack();
				$this->error = e.getMessage();
				return false;
			}
			return true;
		}
		
		public function cancela_transaccion()
		{
			return $this->conexion->rollBack();
		}
		
		public function error()
		{
			return $this->error;
		}
	}