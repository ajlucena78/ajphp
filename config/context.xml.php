﻿<?php
	if (!isset($XML_KEY) or $XML_KEY != date('Ymdh'))
		exit();
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<context>
	<appname>CoolPHP</appname>
	<!-- Origen de datos -->
    <db>
        <url value="mysql:dbname=coolphp;host=localhost" />
        <username value="coolphp" />
        <password value="060402" />
    </db>
	<!-- Services -->
	<service id="usuarioService" class="UsuarioService" />
	<service id="mensajeService" class="MensajeService" />
	<!-- Actions -->
	<action id="usuarioAction" class="UsuarioAction">
    	<service ref="usuarioService" />
    </action>
    <action id="mensajeAction" class="MensajeAction">
    	<service ref="mensajeService" />
    	<service ref="usuarioService" />
    </action>
</context>