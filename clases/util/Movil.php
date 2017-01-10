<?php
	class Movil
	{
		public static function es_navegador_movil()
		{
			if (stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') !== false)
			{
				return true;
			}
			return false;
		}
	}