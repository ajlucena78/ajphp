<?php
	class Imagenes
	{
		public $error;
		
		public static function thumb_jpeg($imagen, $destino, $anchura = null)
		{
			if (!$datos = @getimagesize($imagen))
				return false;
			$ancho_origen = $datos[0];
			$alto_origen = $datos[1];
			if ($anchura)
			{
				if ($ancho_origen < $anchura or $alto_origen < $anchura)
				{
					$_SESSION['error_foto'] = 'El ancho y alto de la imagen no pueden ser menores a ' 
							. $anchura . ' píxeles';
					return false;
				}
				else
				{
					$_SESSION['error_foto'] = '?';
				}
			}
			else
			{
				$anchura = $ancho_origen;
			}
			$x = $y = 0;
			$res = $ancho_origen - $alto_origen;
			if ($res)
			{
				//la imagen no es un cuadrado, luego hay que recortarla para que lo sea
				if ($res > 0)
				{
					//es más ancha que alta
					$x = round($res / 2);
					$ancho_origen = $alto_origen;
				}
				else
				{
					//es más alta que ancha
					$y = round(abs($res) / 2);
					$alto_origen = $ancho_origen;
				}
			}
			if (!$anchura)
			{
				$anchura = $ancho_origen;
			}
			$altura = $anchura;
			if (!$thumb = @imagecreatetruecolor($anchura, $altura))
				return false;
			if (!$img = @imagecreatefromjpeg($imagen))
				return false;
			if (!@imagecopyresampled ($thumb, $img, 0, 0, $x, $y, $anchura, $altura, $ancho_origen, $alto_origen))
				return false;
			if (!@imagejpeg($thumb, $destino))
				return false;
			return true;
		}
		
		public function error()
		{
			return $this->error;
		}
	}