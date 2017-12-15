<?php
	class Cadena
	{
		public static function quita_acentos($cadena)
		{
			$cadena = htmlentities($cadena, ENT_COMPAT, 'UTF-8');
			$cadena = str_replace('&aacute;', 'a', $cadena);
			$cadena = str_replace('&eacute;', 'e', $cadena);
			$cadena = str_replace('&iacute;', 'i', $cadena);
			$cadena = str_replace('&oacute;', 'o', $cadena);
			$cadena = str_replace('&uacute;', 'u', $cadena);
			$cadena = str_replace('&Aacute;', 'A', $cadena);
			$cadena = str_replace('&Eacute;', 'E', $cadena);
			$cadena = str_replace('&Iacute;', 'I', $cadena);
			$cadena = str_replace('&Oacute;', 'O', $cadena);
			$cadena = str_replace('&Uacute;', 'U', $cadena);
			$cadena = str_replace('&ntilde;', 'n', $cadena);
			$cadena = str_replace('&Nacute;', 'N', $cadena);
			$cadena = str_replace('&amp;', 'y', $cadena);
			$cadena = str_replace('&euro;', 'euro', $cadena);
			return $cadena;
		}
		
		public static function genera_permalink($texto)
		{
			$texto = Cadena::quita_acentos($texto);
			$texto = strtolower($texto);
			$palabras = '';
			for ($i = 0; $i < strlen($texto); $i++)
			{
				if (es_letra_o_numero($texto[$i]))
				{
					$palabras .= $texto[$i];
				}
				else
				{
					$palabras .= ' ';
				}
			}
			$palabras = explode(' ', $palabras);
			$permalink = '';
			foreach ($palabras as $palabra)
			{
				if (strlen($palabra) > 2)
				{
					$permalink .= $palabra . '-';
				}
			}
			$permalink = substr($permalink, 0, strlen($permalink) - 1);
			return $permalink;
		}
	}