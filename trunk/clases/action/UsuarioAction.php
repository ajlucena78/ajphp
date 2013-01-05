<?php
	class UsuarioAction extends Action
	{
		protected $usuarioService;
		protected $usuario;
		
		public function __construct($servicios)
		{
			parent::__construct($servicios);
			if ($_GET['action'] != 'index' and $_GET['action'] != 'login')
				if (!isset($_SESSION['usuario']) or !$this->usuarioService->check_usuario($_SESSION['usuario']
						, $_SESSION['session_id']))
					$this->redirect_to_action('index');
		}
		
		public function index()
		{
			$_SESSION['usuario'] = null;
			$this->usuario = new Usuario();
			return 'success';
		}
		
		public function login()
		{
			$this->usuario = new Usuario($_POST);
			if (!$this->usuario->email or !$this->usuario->clave)
			{
				$this->error = 'El correo electrónico y la clave son obligatorios';
				return 'error';
			}
			$this->usuario->clave = md5($this->usuario->clave);
			$res = $this->usuarioService->login($this->usuario);
			if ($res === false)
			{
				$this->error = $this->usuarioService->getError();
				return 'error';
			}
			elseif (!$res)
			{
				$this->error = 'Datos incorrectos';
				return 'error';
			}
			$_SESSION['usuario'] = $this->usuario;
			$_SESSION['session_id'] = md5($this->usuario->email);
			return 'success';
		}
		
		public function logout()
		{
			$_SESSION['usuario'] = null;
			$_SESSION['session_id'] = null;
			return 'success';
		}
		
		public function contactos_usuario()
		{
			/*
			Usamos un objeto de la clase Usuario intermediario para evitar que los contactos se guarden en sesión
			*/
			$this->usuario = new Usuario();
			$this->usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			if ($this->usuario->contactos === false)
				return 'error';
			return 'success';
		}
		
		public function borrar()
		{
			if (!isset($_GET['id_usuario']) or !$_GET['id_usuario'])
				return 'success';
			//localiza el contacto por el ID proporcionado
			$contacto = $this->usuarioService->findById($_GET['id_usuario']);
			if (!$contacto)
				return 'success';
			$usuario = new Usuario();
			$usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			$usuario->contactos = array($contacto);
			if (!$this->usuarioService->exist_relation($usuario, 'contactos'))
				return 'success';
			if (!$this->usuarioService->destroy_relation($usuario, 'contactos'))
				return 'success';
			return 'success';
		}
	}