<?php
	function link_action($action, $params = null, $sinFormato = false)
	{
		if ($sinFormato)
			$amp = '&';
		else
			$amp = '&amp;';
		if ($params and is_array($params) and count($params) > 0)
			foreach ($params as $nombre => $valor)
				$action .= $amp . $nombre . '=' . $valor;
		return '?action=' . $action;
	}
	
	function carga($action, $id = null, $params = null)
	{
		if (!$action)
			return false;
		if (!$id)
			$id = $action;
		$action = link_action($action, $params, true);
		//TODO if (isset($_SESSION['javascript']) or $action == link_action('activa_javascript'))
		if (true)
		{
?>
			<script type="text/javascript">
				<!--
				carga('<?php echo Config::pathApp(); ?><?php echo $action; ?>', '<?php echo $id; ?>');
				//-->
			</script>
<?php
		}
		else
		{
			$url = Config::hostApp() . $_SESSION['config']->getPathApp() . $action;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			$html = curl_exec($ch);
			curl_close($ch);
			if ($html)
				echo $html;
		}
		return true;
	}
	
	function vlink($action, $params = null, $sinFormato = false)
	{
		$action = link_action($action, $params, $sinFormato);
		echo $action;
	}
	
	function olink($action, $dest = null, $params = null, $ocultar = false, $pregunta = null, $funcion = null
			, $add_html = false, $ancla = null, $clase = null, $style = null, $title = null)
	{
		$action = link_action($action, $params, true);
		?><a href="<?php if ($pregunta) echo 'javascript:'; else echo $action; ?>"<?php if ($dest) { 
				?> onclick="<?php if ($pregunta) echo 'if (pregunta(\'' . htmlentities($pregunta) . '\')) '; 
				?>carga('<?php echo $action; ?>', '<?php echo $dest; ?>', <?php 
				if ($funcion) echo '\'' . str_replace('"', "\'", $funcion) 
				. '\''; else echo 'null'; ?>, null<?php if ($ocultar) { ?>, true<?php } ?><?php 
				if ($add_html) { ?>, false, true<?php } ?><?php if ($ancla) { ?>, '<?php echo $ancla; ?>'<?php
				} ?>); return false;"<?php } ?><?php if ($clase){echo ' class="' . $clase . '"';} ?><?php 
				if ($style){echo ' style="' . $style . '"';} ?><?php 
				if ($title){echo ' title="' . $title . '"';} ?>><?php
	}
	
	function clink()
	{
		?></a><?php
	}
	
	function carga_rotativa($action, $tiempo, $id = null)
	{
		if (!$action)
			return false;
		if (!$id)
			$id = $action;
		//TODO if (isset($_SESSION['javascript']) or $action == 'activa_javascript')
		if (true)
		{
?>
			<script type="text/javascript">
				carga_rotativa('<?php vlink($action); ?>', <?php echo $tiempo; ?>, '<?php echo $id; ?>');
			</script>
<?php
		}
		else
		{
			$url = Config::hostApp() . $_SESSION['config']->getPathApp() . link_action($action);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
			$html = curl_exec($ch);
			curl_close($ch);
			if ($html)
				echo $html;
		}
	}