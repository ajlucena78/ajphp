<?php
	class Cadena
	{
		public static function quita_acentos($cadena)
		{
			$tam = strlen($cadena);
			$res = '';
			for ($i = 0; $i < $tam; $i++)
			{
				if ($cadena[$i] == '�')
					$res .= 'a';
				elseif ($cadena[$i] == '�')
					$res .= 'e';
				elseif ($cadena[$i] == '�')
					$res .= 'i';
				elseif ($cadena[$i] == '�')
					$res .= 'o';
				elseif ($cadena[$i] == '�')
					$res .= 'u';
				else
					$res .= $cadena[$i];
			}
			return $res;
		}
	}