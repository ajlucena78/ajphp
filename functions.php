<?php
	function link_action($action, $params = null, $sinFormato = false)
	{
		if ($sinFormato)
			$amp = '&';
		else
			$amp = '&amp;';
		if ($params and is_array($params))
		{
			$numParams = count($params);
			if ($numParams > 0)
			{
				$action .= '?';
				$cont = 1;
				foreach ($params as $nombre => $valor)
				{
					$action .= $nombre . '=' . $valor;
					if ($cont++ < $numParams)
						$action .= $amp;
				}
			}
		}
		return URL_APP . $action;
	}
	
	function carga($action, $id = null, $params = null, $funcion = null)
	{
		if (!$action)
			return false;
		if (!$id)
			$id = $action;
		$action = link_action($action, $params, true);
		if (!$funcion)
			$funcion = 'null';
		else
			$funcion = "'$funcion'";
?>
		<script type="text/javascript">
			<!--
			carga('<?php echo $action; ?>', '<?php echo $id; ?>', <?php echo $funcion; ?>);
			//-->
		</script>
<?php
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
				?> onclick="<?php if ($pregunta) echo 'if (pregunta(\'' . formato_html($pregunta) . '\')) '; 
				?>carga('<?php echo $action; ?>', '<?php echo $dest; ?>', <?php 
				if ($funcion) echo '\'' . str_replace('"', "\'", $funcion) 
				. '\''; else echo 'null'; ?>, null<?php if ($ocultar) { ?>, true<?php } ?><?php 
				if ($add_html) { ?>, false, true<?php } ?><?php if ($ancla) { ?>, '<?php echo $ancla; ?>'<?php
				} ?>); return false;"<?php } ?><?php if ($clase){echo ' class="' . $clase . '"';} ?><?php 
				if ($style){echo ' style="' . $style . '"';} ?><?php 
				if ($title){echo ' title="' . $title . '"';} ?>><?php
	}
	
	function vlinkAjax($action, $dest = null, $params = null, $ocultar = false, $funcion = null
			, $add_html = false, $ancla = null)
	{
		$action = link_action($action, $params, true);
		$html = 'javascript:carga(\'' . $action . '\', \'' . $dest . '\', \'' . $funcion . '\'';
		$html .= ', null, \'' . $ocultar . '\', \'' . $add_html . '\', \'' . $ancla . '\', true);';
		return $html;
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
?>
		<script type="text/javascript">
			carga_rotativa('<?php vlink($action); ?>', <?php echo $tiempo; ?>, '<?php echo $id; ?>');
		</script>
<?php
	}
	
	function formato_html($texto)
	{
		return htmlentities($texto, ENT_XHTML, 'ISO-8859-1');
		//return htmlentities($texto);
	}