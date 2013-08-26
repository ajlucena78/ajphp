<?php
	class Consulta
	{
		private static $conexion;
		private $consulta;
		private $error;
		
		public function Consulta($conexion)
		{
			if (!self::$conexion)
				self::$conexion = $conexion;
		}
		
		private function msj_error()
		{
			$datos = $this->consulta->errorInfo();
			if (!isset($datos[2]))
				return null;
			return $datos[2];
		}
		
		public function ejecuta($sql, $datos = null)
		{
			$this->consulta = self::$conexion->carga_consulta($sql);
			$res = $this->consulta->execute($datos);
			if ($res === false)
				$this->error = 'Error en la ejecución de la consulta ' . $sql . ': ' . $this->msj_error();
			return $res;
		}
		
		public function lee_registro()
		{
			return $this->consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		public function libera()
		{
			$this->consulta = null;
		}
		
		public function error()
		{
			return $this->error;
		}
	}