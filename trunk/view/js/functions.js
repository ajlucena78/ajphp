function desactiva_form(id)
{
	var form = document.getElementById(id);
	if (!form)
		return false;
	for (var i = 0; i < form.elements.length; i++)
		form.elements[i].disabled = true;
	return true;
}

function activa_form(id)
{
	var form = document.getElementById(id);
	if (!form)
		return false;
	for (var i = 0; i < form.elements.length; i++)
		form.elements[i].disabled = false;
	return true;
}

function ver_popup(ancho, alto, url, funcion)
{
	var capa = document.getElementById('fondo_oscuro');
	if (!capa)
		return false;
	capa.style.display = 'table';
	var capa = document.getElementById('popup_fondo');
	if (!capa)
		return false;
	var popup = document.getElementById('popup');
	if (!popup)
		return false;
	var contenido_popup = document.getElementById('contenido_popup');
	if (!contenido_popup)
		return false;
	if (!ancho)
		ancho = 640;
	if (ancho > getWindowWidth())
		ancho = getWindowWidth() - 40;
	popup.style.width = ancho + 'px';
	contenido_popup.style.width = ancho + 'px';
	if (!alto)
		alto = 480;
	if (alto > getWindowHeight())
		alto = getWindowHeight() - 40;
	popup.style.height = alto + 'px';
	contenido_popup.style.height = (alto - 20) + 'px';
	if (url)
	{
		contenido_popup.innerHTML = '';
		carga(url, 'contenido_popup', funcion);
		contenido_popup.innerHTML = '<br />';
		contenido_popup.innerHTML += '<img src="/res/img/web/ajax-loader.gif" alt="Cargando..." />';
		contenido_popup.innerHTML += '<br /><strong>Cargando...</strong>';
	}
	capa.style.display = 'table';
}

function quitar_popup()
{
	var capa = document.getElementById('fondo_oscuro');
	if (!capa)
		return false;
	capa.style.display = 'none';
	var capa = document.getElementById('popup_fondo');
	if (!capa)
		return false;
	capa.style.display = 'none';
}

/**
 * Aplica una transparencia a una capa, dado el id de la misma, a un nivel dado del 0 al 9 
 * @param id
 * @param nivel
 * @returns {Boolean}
 */
function transparencia(id, nivel)
{
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	if (capa.style.opacity)
		capa.style.opacity = nivel / 10;
	if (capa.filters)
		capa.filters.alpha.opacity = nivel * 10;
	return true;
}

function get_transparencia(id)
{
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	if (capa.style.opacity)
		return capa.style.opacity;
	if (capa.filters)
		return capa.filters.alpha.opacity;
	return false;
}

function removeElement(id)
{
	var elem = document.getElementById(id);
	if (elem)
		elem.parentNode.removeChild(elem);
}

function ocultar_capa(id, fundido, quitar, nivel)
{
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	if (fundido)
	{
		if (get_transparencia(id) >= 0)
		{
			if (isNaN(nivel))
				nivel = 9;
			else
				nivel -= 1;
			transparencia(id, nivel);
			setTimeout('ocultar_capa("' + id + '", ' + fundido + ', ' + quitar + ', ' + nivel + ');', 100);
			return true;
		}
	}
	else
		transparencia(id, 0);
	capa.style.display = 'none';
	if (quitar)
		removeElement(id);
	return true;
}

function mostrar_capa(id, fundido, nivel)
{
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	capa.style.display = 'block';
	if (fundido)
	{
		if (get_transparencia(id) < 1)
		{
			if (isNaN(nivel))
				nivel = 1;
			else
				nivel += 1;
			transparencia(id, nivel);
			setTimeout('mostrar_capa("' + id + '", ' + fundido + ', ' + nivel + ');', 100);
		}
	}
	else
		transparencia(id, 10);
	return true;
}

function mostrar_ocultar(id)
{
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	if (!capa.style.display || capa.style.display == 'block')
		ocultar_capa(id);
	else
		mostrar_capa(id);
	return true;
}

function ir_a(url)
{
	window.location.href = url;
}

function getWindowHeight()
{
  return window.innerHeight;
}

function getWindowWidth()
{
  return window.innerWidth;
}

function carga_rotativa(url, segundos, id)
{
	var capaGeneradora = document.getElementById(id + '_generadora');
	if (capaGeneradora)
	{
		var html = capaGeneradora.innerHTML;
		var capa = capaGeneradora.parentNode;
		capa.innerHTML = html;
		noTransparencia = false;
	}
	else
		noTransparencia = true;
	carga(url, null, 'muestra_rotativa', new Array(url, id, segundos, noTransparencia));
}

function muestra_rotativa(cargador, param)
{
	var url = param[0];
	var id = param[1];
	var segundos = param[2];
	var noTransparencia = param[3];
	if (noTransparencia)
		var fundido = false;
	else
		var fundido = true;
	var capa = document.getElementById(id);
	if (!capa)
		return false;
	if (capa.innerHTML != cargador.responseText)
	{
		capa.style.position = 'relative';
		var html = capa.innerHTML;
		capa.innerHTML = '<div id="' + id + '_borradora" style="position: absolute; z-index: 2; top: -1px; left: -1px; opacity: 1; filter: alpha(opacity=100); width: 100%;"></div>';
		var capaBorradora = document.getElementById(id + '_borradora');
		if (!capaBorradora)
			return false;
		capaBorradora.innerHTML = html;
		capa.innerHTML += '<div id="' + id + '_generadora" style="position: absolute; z-index: 1; top: -1px; left: -1px; opacity: 0.0; filter: alpha(opacity=0); width: 100%;"></div>';
		var capaGeneradora = document.getElementById(id + '_generadora');
		if (!capaGeneradora)
			return false;
		capaGeneradora.innerHTML = cargador.responseText;
		ocultar_capa(id + '_borradora', fundido, true);
		mostrar_capa(id + '_generadora', fundido);
	}
	setTimeout('carga_rotativa("' + url + '", ' + segundos + ', "' + id + '")', (segundos * 1000));
}

function pregunta(mensaje)
{
	if (confirm(mensaje))
		return true;
	return false;
}