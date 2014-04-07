<?php
	class Cadena
	{
		public static function quita_acentos($cadena)
		{
			$tam = strlen($cadena);
			$res = '';
			for ($i = 0; $i < $tam; $i++)
			{
				if ($cadena[$i] == 'á')
					$res .= 'a';
				elseif ($cadena[$i] == 'é')
					$res .= 'e';
				elseif ($cadena[$i] == 'í')
					$res .= 'i';
				elseif ($cadena[$i] == 'ó')
					$res .= 'o';
				elseif ($cadena[$i] == 'ú')
					$res .= 'u';
				else
					$res .= $cadena[$i];
			}
			return $res;
		}
	}