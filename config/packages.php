<?php 
	if (!isset($_SESSION['key']) or !isset($key) or $key != $_SESSION['key'])
		exit();
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<packages>
	<package name="usuario">
    	<action name="index" method="index" class="usuarioAction">
            <result name="success">/index.php</result>
        </action>
        <action name="login" method="login" class="usuarioAction">
        	<result name="success">mensajes</result>
        	<result name="error">/index.php</result>
        </action>
        <action name="logout" method="logout" class="usuarioAction">
            <result name="success">index</result>
        </action>
        <action name="contactos" method="contactos_usuario" class="usuarioAction">
        	<result name="success">/contactos.php</result>
        	<result name="error">/error.php</result>
        </action>
        <action name="borrar_contacto" method="borrar" class="usuarioAction">
        	<result name="success"></result>
        </action>
	</package>
	<package name="mensaje">
        <action name="mensajes" method="mensajes_usuario" class="mensajeAction">
        	<result name="success">/mensajes.php</result>
        </action>
        <action name="mensajes_recibidos" method="mensajes_recibidos_usuario" class="mensajeAction">
        	<result name="success">/includes/mensajes_recibidos.php</result>
        	<result name="error">/includes/error.php</result>
        </action>
        <action name="mensajes_enviados" method="mensajes_enviados_usuario" class="mensajeAction">
        	<result name="success">/includes/mensajes_enviados.php</result>
        	<result name="error">/includes/error.php</result>
        </action>
        <action name="borrar_mensaje" method="borrar" class="mensajeAction">
        	<result name="success">mensajes_recibidos</result>
        	<result name="error">/includes/error.php</result>
        </action>
        <action name="nuevo_mensaje" method="form_nuevo" class="mensajeAction">
        	<result name="success">/form_nuevo_mensaje.php</result>
        	<result name="error">/error.php</result>
        </action>
        <action name="envia_mensaje" method="envia" class="mensajeAction">
        	<result name="success">/mensaje_enviado.php</result>
        	<result name="error">/form_nuevo_mensaje.php</result>
        	<result name="fatal">/error.php</result>
        </action>
	</package>
</packages>