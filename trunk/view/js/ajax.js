function carga(url, id, funcion, param, ocultar, add_html, ancla)
{
	var clase = null;
	var cargador = inicia_cargador();
	if (!cargador)
		return false;
	if (ocultar)
	{
		removeElement(id);
	}
	else
	{
		if (id && !add_html)
		{
			var capa = document.getElementById(id);
			if (capa && capa.innerHTML)
			{
				clase = capa.className;
				capa.className += ' cargando';
			}
		}
	}
	cargador.onreadystatechange = function()
	{
		//llamada a la función que carga la página
		cargaResultado(cargador, id, funcion, param, ocultar, add_html, ancla, clase);
	};
	url += '&ajax=1';
	// métodos open y send
	cargador.open('GET', url, true);
	cargador.send(null);
	return true;
}

//función que presenta la información
function cargaResultado(cargador, id, funcion, param, ocultar, add_html, ancla, clase)
{
	if (cargador.readyState == 4 && (cargador.status == 200 || window.location.href.indexOf("http") == -1))
	{
		if (id)
		{
			var capa = document.getElementById(id);
			if (capa)
			{
				if (ocultar && cargador.responseText == '')
					removeElement(id);
				else
				{
					if (clase)
					{
						clase = clase.replace(' cargando', '');
						capa.className = clase;
					}
					else
						capa.className = '';
					capa.style.display = 'block';
					if (add_html)
						capa.innerHTML += cargador.responseText;
					else
						capa.innerHTML = cargador.responseText;
					if (ancla)
						window.location.href = '#' + ancla;
				}
			}
		}
		if (funcion)
		{
			if (funcion.substr(funcion.length - 1, 1) != ')')
				funcion += '(cargador, param)';
			eval(funcion);
		}
	}
}

function envia_form(formId, id, funcion)
{
	var clase = null;
	var peticion = inicia_cargador();
	if (!peticion)
		return false;
	desactiva_form(formId);
	if (id)
	{
		capa = document.getElementById(id);
		if (capa)
		{
			clase = capa.className;
			capa.className += ' cargando';
		}
	}
    var formulario = document.getElementById(formId);
    if (!formulario)
    	return false;
    var longitudFormulario = formulario.elements.length;
    var cadenaFormulario = '';
    var sepCampos = '';
    for (var i = 0; i <= formulario.elements.length - 1; i++)
    {
    	if (formulario.elements[i].type == 'radio' && formulario.elements[i].checked == false)
    		continue;
    	cadenaFormulario += sepCampos + formulario.elements[i].name + '=' 
    			+ encodeURI(formulario.elements[i].value);
    	sepCampos = '&';
    }
    peticion.open('POST', formulario.action, true);
    peticion.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    peticion.onreadystatechange = function ()
    {
    	if (peticion.readyState == 4)
    	{
    		if (id)
    		{
	    		capa = document.getElementById(id);
	    		if (capa)
	    		{
	    			if (clase)
					{
						clase = clase.replace(' cargando', '');
						capa.className = clase;
					}
					else
						capa.className = '';
					capa.innerHTML = peticion.responseText;
					activa_form(formId);
	    		}
    		}
    		if (funcion)
    			eval(funcion);
    	}
    };
    peticion.send(cadenaFormulario);
}

function inicia_cargador()
{
	var cargador = null;
	if (window.XMLHttpRequest)
	{
		//comprueba si el navegador es opera, safari, mozilla, etc.
		cargador = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		//comprueba si el navegador es internet explorer
		try
		{
			cargador = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			//caso de versión antigua de internet explorer
			try
			{
				cargador = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e)
			{
				return(false);
			}
		}
	}
	else
	{
		return false;
	}
	return cargador;
}