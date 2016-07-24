<?php
	class Config
	{
		private $services;
		private $actions;
		private $actionPackages;
		private $frames;
		private $app_name;
		private $db_url;
		private $db_username;
		private $db_password;
		private $url_app;
		private $path_view;
		private $url_view;
		private $host_app;
		
		public function Config()
		{
			//url relativa al proyecto
			$pathInfo = pathinfo($_SERVER['SCRIPT_NAME']);
			if ($pathInfo['dirname'] == '/' or $pathInfo['dirname'] == '\\')
				$urlApp = '';
			else
				$urlApp = $pathInfo['dirname'];
			$this->url_app = $urlApp . '/';
			//dirección del dominio del proyecto
			if (isset($_SERVER['HTTP_HOST']))
			{
				$this->host_app = 'http://' . $_SERVER['HTTP_HOST'];
			}
			//carga de los ficheros de configuración y mapa de acciones
			$xml = $this->carga_xml('context.xml.php');
			$this->app_name = strval(utf8_decode($xml->appname));
			//conexión a la base de datos
			$this->db_url = '' . $xml->db->url->attributes();
			$this->db_username = '' . $xml->db->username->attributes();
			$this->db_password = '' . $xml->db->password->attributes();
			$this->services = array();
			foreach ($xml->service as $service)
				$this->services['' . $service['id']] = '' . $service['class'];
			$this->actions = array();
			foreach ($xml->action as $action)
			{
				$this->actions['' . $action['id']]['class'] = '' . $action['class'];
				$this->actions['' . $action['id']]['services'] = array();
				foreach ($action->children() as $service)
					$this->actions['' . $action['id']]['services'][] = '' . $service['ref'];
			}
			if ($_SESSION['navegador'] == 'movil')
			{
				//$viewPath = '/movil';
				$viewPath = '';
				$xml = $this->carga_xml('packages_movil.xml.php');
			}
			else
			{
				$viewPath = '';
				$xml = $this->carga_xml('packages.xml.php');
			}
			$this->actionPackages = array();
			foreach ($xml->package as $package)
			{
				$atributosPack = $package->attributes();
				$nombrePackage = '' . $atributosPack['name'];
				foreach ($package as $action)
				{
					$atributos = $action->attributes();
					$this->actionPackages['' . $atributos['name']] = array();
					$this->actionPackages['' . $atributos['name']]['package'] = $nombrePackage;
					$this->actionPackages['' . $atributos['name']]['method'] = '' . $atributos['method'];
					$this->actionPackages['' . $atributos['name']]['class'] = '' . $atributos['class'];
					if (isset($atributos['frame']))
					{
						$this->actionPackages['' . $atributos['name']]['frame'] = '' . $atributos['frame'];
					}
					elseif (isset($atributosPack['frame']))
					{
						$this->actionPackages['' . $atributos['name']]['frame'] = '' . $atributosPack['frame'];
					}
					$this->actionPackages['' . $atributos['name']]['results'] = array();
					foreach ($action as $result)
					{
						$atributos2 = $result->attributes();
						$this->actionPackages['' . $atributos['name']]['results']['' . $atributos2['name']] = '' 
								. $result[0];
					}
				}
			}
			if (file_exists(APP_ROOT . '/config/frames.xml.php'))
			{
				$xml = $this->carga_xml('frames.xml.php');
				$this->frames = array();
				foreach ($xml->frame as $frame)
				{
					$this->frames['' . $frame['id']] = '' . $frame[0];
				}
			}
			//ruta física al directorio de las vistas view 
			$this->path_view = $_SERVER['DOCUMENT_ROOT'] . $urlApp . '/view' . $viewPath . '/';
			//url relativa al directorio de las vistas view
			$this->url_view = $urlApp . '/view' . $viewPath . '/';
		}
		
		private function carga_xml($archivo)
		{
			$XML_KEY = date('Ymdh');
			ob_start();
			include APP_ROOT . 'config/' . $archivo;
			unset($XML_KEY);
			$res = ob_get_contents();
			ob_end_clean();
			return simplexml_load_string($res);
		}
		
		public function service($service)
		{
			if (isset($this->services[$service]))
				return $this->services[$service];
			return null;
		}
		
		public function action($action)
		{
			if (isset($this->actions[$action]))
				return $this->actions[$action];
			return null;
		}
		
		public function action_by_class($class)
		{
			foreach ($this->actions as $action => $classAction)
				if ($classAction = $class)
					return $this->actions[$action];
			return null;
		}
		
		public function actionPackage($actionPackage)
		{
			if (isset($this->actionPackages[$actionPackage]))
				return $this->actionPackages[$actionPackage];
			return null;
		}
		
		public static function app_root()
		{
			$pathInfo = pathinfo($_SERVER['SCRIPT_NAME']);
			if ($pathInfo['dirname'] == '/' or $pathInfo['dirname'] == '\\')
				$urlApp = '';
			else
				$urlApp = $pathInfo['dirname'];
			return $_SERVER['DOCUMENT_ROOT'] . $urlApp;
		}
		
		public function frame($frame)
		{
			if (isset($this->frames[$frame]))
				return $this->frames[$frame];
			return null;
		}
		
		public function app_name()
		{
			return $this->app_name;
		}
		
		public function db_url()
		{
			return $this->db_url;
		}
		
		public function db_username()
		{
			return $this->db_username;
		}
		
		public function db_password()
		{
			return $this->db_password;
		}
		
		public function url_app()
		{
			return $this->url_app;
		}
		
		public function path_view()
		{
			return $this->path_view;
		}
		
		public function url_view()
		{
			return $this->url_view;
		}
		
		public function host_app()
		{
			return $this->host_app;
		}
	}