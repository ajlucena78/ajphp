<?php
	class Fecha
	{
		public static function convierte_BBDD_a_web($fecha)
		{
			$tiempo = mktime() - strtotime($fecha);
			$res = 'Hace ';
			if ($tiempo < 60)
				$res .= 'menos de un minuto';
			elseif ($tiempo < 3600)
				$res .= 'menos de una hora';
			elseif ($tiempo < 86400)
				$res .= 'menos de un día';
			elseif ($tiempo < 604800)
				$res .= 'menos de una semana';
			elseif ($tiempo < 2592000)
				$res .= 'menos de un mes';
			elseif ($tiempo < 31104000)
				$res .= 'menos de un año';
			else
				$res .= 'mucho tiempo';
			return htmlentities($res);
		}
		
		public static function fecha_hoy()
		{
			return date('Y-m-d H:i:s');
		}
		
		public static function convierte_BBDD_a_spa($fecha, $verHora = false)
		{
			$datosFecha = explode(' ', $fecha);
			$datos = explode('-', $datosFecha[0]);
			$fecha_sal = date($datos[2] . '/' . $datos[1] . '/' . $datos[0]);
			if ($verHora and $datos[1])
			{
				$datos = explode(':', $datosFecha[1]);
				$fecha_sal .= " " . $datos[0] . ":" . $datos[1];
			}
			return $fecha_sal;
		}
		
		public static function convierte_SQL($fecha)
		{
			$datosFecha = explode(' ', $fecha);
			$datos = explode('/', $datosFecha[0]);
			$fecha_sal = date($datos[2] . '-' . $datos[1] . '-' . $datos[0]);
			if ($datos[1])
				$fecha_sal .= " " . $datosFecha[1];
			return $fecha_sal;
		}
	}