<?php
	class Email
	{
		public static function check_email_address($email) 
		{
			$mail_correcto = 0;
			//compruebo unas cosas primeras
			if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@"))
			{
			   if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) 
			   {
				  //miro si tiene caracter .
				  if (substr_count($email,".")>= 1)
				  {
					 //obtengo la terminacion del dominio
					 $term_dom = substr(strrchr ($email, '.'),1);
					 //compruebo que la terminación del dominio sea correcta
					 if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) )
					 {
						//compruebo que lo de antes del dominio sea correcto
						$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
						$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
						if ($caracter_ult != "@" && $caracter_ult != ".")
						{
						   $mail_correcto = 1;
						}
					 }
				  }
			   }
			}
			if ($mail_correcto)
			   return true;
			return false;
		}
	}