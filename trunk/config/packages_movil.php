<?php 
	if (!isset($_SESSION['key']) or !isset($key) or $key != $_SESSION['key'])
		exit();
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<packages>
	<!--
    <package name="paquete">
    	<action name="action" method="metodo" class="clase">
            <result name="success">/view/movil/vista.php</result>
        </action>
    </package>
    -->
</packages>