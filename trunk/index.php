<?php
	date_default_timezone_set('Europe/Madrid');
	require_once 'clases/Config.php';
	require_once 'clases/Action.php';
	require_once 'clases/Model.php';
	require_once 'clases/Service.php';
	
	if (isset($argv[1]) and $argv[1])
		$_GET['action'] = $argv[1];
	elseif (!isset($_GET['action']) or !$_GET['action'])
		$_GET['action'] = 'index';
	define('APP_ROOT', Config::app_root() . '/');
	include 'requires.php';
	include 'conf.php';
	session_start();
	//redirige a la versión móvil si es un navegador de estos
	/*
	if (!isset($_SESSION['navegador']))
	{
		require_once 'clases/util/Movil.php';
		if (isset($_SERVER['HTTP_USER_AGENT']) and Movil::es_navegador_movil())
			$_SESSION['navegador'] = 'movil';
		else
			$_SESSION['navegador'] = 'desktop';
	}
	*/
	$_SESSION['navegador'] = 'desktop';
	//TODO if (!isset($_SESSION['config']))
		$_SESSION['config'] = new Config();
	//url relativa al proyecto
	define('URL_APP', $_SESSION['config']->url_app());
	//url relativa al directorio res
	define('URL_RES', URL_APP . 'res/');
	//nombre de la aplicación
	define('APP_NAME', $_SESSION['config']->app_name());
	//conexión a la base de datos
	define('DB_URL', '' . $_SESSION['config']->db_url());
	define('DB_USERNAME', '' . $_SESSION['config']->db_username());
	define('DB_PASSWORD', '' . $_SESSION['config']->db_password());
	//ruta física al directorio de las vistas view 
	define('PATH_VIEW', $_SESSION['config']->path_view());
	//url relativa al directorio de las vistas view
	define('URL_VIEW', $_SESSION['config']->url_view());
	//host del sitio
	define('HOST_APP', $_SESSION['config']->host_app());
	include 'functions.php';
	if (!$actionPackage = $_SESSION['config']->actionPackage($_GET['action']))
	{
		//es una permalink de una página
		$_GET['permalink'] = $_GET['action'];
		$_GET['action'] = 'index';
		if (!$actionPackage = $_SESSION['config']->actionPackage($_GET['action']))
			exit();
	}
	if (!$action = $_SESSION['config']->action($actionPackage['class']))
		exit();
	$action = Action::cargaAction($action['class'], $action['services']);
	if ($action->error())
	{
		echo $action->error();
		exit();
	}
	define('ACTION', $_GET['action']);
	define('PACKAGE', $actionPackage['package']);
	$view = $action->$actionPackage['method']();
	if ($view !== null and isset($actionPackage['results'][$view]) and $actionPackage['results'][$view])
	{
		$action->to_view();
		if (file_exists(PATH_VIEW . $actionPackage['results'][$view]))
		{
			if (isset($actionPackage['frame']) and $actionPackage['frame'])
			{
				$frame = $_SESSION['config']->frame($actionPackage['frame']);
				if (!isset($frame))
				{
					echo 'Error: No se encuentra el marco: ' . $actionPackage['frame'];
					exit();
				}
				$FILE_VIEW = PATH_VIEW . $actionPackage['results'][$view];
				include(PATH_VIEW . $frame);
			}
			else
			{
				include(PATH_VIEW . $actionPackage['results'][$view]);
			}
		}
		else
		{
			$action = link_action($actionPackage['results'][$view]);
			if (isset($_SESSION['get']) and is_array($_SESSION['get']) and count($_SESSION['get']) > 0)
			{
				foreach ($_SESSION['get'] as $var => $valor)
					$action .= '&' . $var . '=' . $valor;
				$_SESSION['get'] = null;
			}
			header('Location:' . $action);
		}
	}