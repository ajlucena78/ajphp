<?php
	date_default_timezone_set('Europe/Madrid');
	require_once 'clases/Config.php';
	define ('APP_ROOT', Config::rootApp());
	require_once 'clases/Action.php';
	require_once 'clases/Model.php';
	require_once 'clases/Service.php';
	
	if (isset($argv[1]) and $argv[1])
		$_GET['action'] = $argv[1];
	elseif (!isset($_GET['action']) or !$_GET['action'])
		$_GET['action'] = 'index';
	include 'requires.php';
	include 'conf.php';
	session_start();
	/*
	//redirige a la versión móvil si es un navegador de estos
	if (!isset($_SESSION['navegador']))
	{
		require_once 'clases/util/Movil.php';
		if (Movil::es_navegador_movil())
			$_SESSION['navegador'] = 'movil';
		else
			$_SESSION['navegador'] = 'desktop';
	}
	*/
	$_SESSION['navegador'] = 'desktop';
	if (!isset($_SESSION['key']))
		$_SESSION['key'] = 'amparo';
	//TODO if (!isset($_SESSION['config']))
		$_SESSION['config'] = new Config();
	$db = $_SESSION['config']->getDb();
	define('DB_URL', $db['url']);
	define('DB_USERNAME', $db['username']);
	define('DB_PASSWORD', $db['password']);
	define('APP_NAME', $_SESSION['config']->getAppName());
	include 'functions.php';
	if (!$actionPackage = $_SESSION['config']->getActionPackage($_GET['action']))
		exit();
	if (!$action = $_SESSION['config']->getAction($actionPackage['class']))
		exit();
	require_once('clases/action/' . $action['class'] . '.php');
	foreach ($action['services'] as $service)
		require_once('clases/service/' . ucfirst($service) . '.php');
	$action = Action::cargaAction($action['class'], $action['services']);
	if ($action->getError())
	{
		echo $action->getError();
		exit();
	}
	$view = $action->$actionPackage['method']();
	if ($view !== null and isset($actionPackage['results'][$view]) and $actionPackage['results'][$view])
	{
		$action->to_view();
		if (file_exists(APP_ROOT . '/view' . $actionPackage['results'][$view]))
		{
			include(APP_ROOT . '/view' . $actionPackage['results'][$view]);
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