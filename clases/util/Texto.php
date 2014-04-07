<?php
	class Texto
	{
		public static function texto_abreviado($texto, $caracteres = 0, $cadena = null)
		{
			$texto = str_replace('[imagen]', '', $texto);
			$texto = str_replace('[video]', '', $texto);
			if (strlen($texto) > $caracteres)
			{
				if ($cadena)
				{
					$i = stripos($texto, $cadena);
					if ($i === false)
						$i = 0;
					else
					{
						if (($i + $caracteres) > strlen($texto))
							$i = strlen($texto) - $caracteres;
					}
					$texto = substr($texto, $i, $caracteres);
					$texto .= '...';
				}
			}
			return $texto;
		}
	}