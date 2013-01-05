<?php
	class Movil
	{
		public static function es_navegador_movil()
		{
			$mobile_browser = '0';
			
			//$_SERVER['HTTP_USER_AGENT'] -> el agente de usuario que est� accediendo a la p�gina.
			//preg_match -> Realizar una comparaci�n de expresi�n regular
			if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i',strtolower($_SERVER['HTTP_USER_AGENT']))){
			    $mobile_browser++;
			}
			
			//$_SERVER['HTTP_ACCEPT'] -> Indica los tipos MIME que el cliente puede recibir. 
			if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or
			    ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))){
			    $mobile_browser++;
			}
			
			$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
			$mobile_agents = array(
			    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
			    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			    'wapr','webc','winw','winw','xda','xda-');
			
			//buscar agentes en el array de agentes
			if(in_array($mobile_ua,$mobile_agents)){
			    $mobile_browser++;
			}
			
			//$_SERVER['ALL_HTTP'] -> Todas las cabeceras HTTP
			//strpos -> Primera aparicion de una cadena dentro de otra
			if(isset($_SERVER['ALL_HTTP']) and strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
			    $mobile_browser++;
			}
			if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
			    $mobile_browser=0;
			}
			
			if($mobile_browser>0){
			        // Mostrar contenido para dispositivos m�viles
			        // Estos pueden ser m�s ligeros: un titulo, resumen y algunos enlaces.
			        // Aca puede redirigir a la ruta donde este el contenido para moviles
			        // Por ejemplo: http://miweb.com/movil � http://movil.miweb.com
			    return true;
			}
			else
			{
				//Contenido que se puede apreciar en navegadores de escritorio
				return false;
			}
		}
	}