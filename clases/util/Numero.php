<?php
	class Numero
	{
		public static function convierte_BBDD_a_web($numero, $decimales = 2)
		{
			$numero += 0;
			if (intval($numero) == $numero)
				$decimales = 0;
			$numero = str_replace(',', '.', $numero);
			return str_replace(',', '.', number_format($numero, $decimales));
		}
	}