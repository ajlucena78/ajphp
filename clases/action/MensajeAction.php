<?php
	class MensajeAction extends Action
	{
		protected $mensajeService;
		protected $usuarioService;
		protected $mensajes;
		protected $usuario;
		protected $errores;
		protected $mensaje;
		
		public function __construct($servicios)
		{
			parent::__construct($servicios);
			if (!isset($_SESSION['usuario']) or !$this->usuarioService->check_usuario($_SESSION['usuario']
					, $_SESSION['session_id']))
				$this->redirect_to_action('index');
		}
		
		public function mensajes_usuario()
		{
			return 'success';
		}
		
		public function mensajes_recibidos_usuario()
		{
			/*
			Usamos un objeto de la clase Usuario intermediario para evitar que los mensajes se guarden en sesión
			*/
			$usuario = new Usuario;
			$usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			$this->mensajes = $usuario->mensajes_recibidos;
			if ($this->mensajes === false)
				return 'error';
			return 'success';
		}
		
		public function mensajes_enviados_usuario()
		{
			/*
			Usamos un objeto de la clase Usuario intermediario para evitar que los mensajes se guarden en sesión
			*/
			$usuario = new Usuario;
			$usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			$this->mensajes = $usuario->mensajes_enviados(10);
			if ($this->mensajes === false)
				return 'error';
			return 'success';
		}
		
		public function borrar()
		{
			if (!isset($_GET['id_mensaje']) or !$_GET['id_mensaje'])
				return 'error';
			//localiza el mensaje por el ID proporcionado
			$mensaje = $this->mensajeService->findById($_GET['id_mensaje']);
			if (!$mensaje)
				return 'error';
			//comprueba si el mensaje pertenece al usuario de la sesión
			if ($mensaje->usuario_destino->id_usuario != $_SESSION['usuario']->id_usuario 
					and $mensaje->usuario_envia->id_usuario != $_SESSION['usuario']->id_usuario)
				return 'error';
			if (!$this->mensajeService->removeById($mensaje->id_mensaje))
				return 'error';
			return 'success';
		}
		
		public function form_nuevo()
		{
			$this->mensaje = new Mensaje;
			/*
			Usamos un objeto de la clase Usuario intermediario para evitar que los contactos se guarden en sesión
			*/
			$this->usuario = new Usuario;
			$this->usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			if ($this->usuario->contactos === false)
				return 'error';
			return 'success';
		}
		
		public function envia()
		{
			$this->mensaje = new Mensaje($_POST);
			$this->mensaje->id_mensaje = uniqid();
			$this->mensaje->usuario_envia = $_SESSION['usuario'];
			//TODO Usamos un objeto de la clase Usuario intermediario para evitar que los contactos se guarden en sesión
			$this->usuario = new Usuario;
			$this->usuario->id_usuario = $_SESSION['usuario']->id_usuario;
			if ($this->usuario->contactos === false)
				return 'fatal';
			if (!isset($this->usuario->contactos[$_POST['usuario_destino']]))
			{
				$this->error = 'El usuario destino indicado no es contacto tuyo';
				return 'error';
			}
			$this->mensaje->usuario_destino = $this->usuario->contactos[$_POST['usuario_destino']];
			$this->mensaje->fecha_envio = date('Y-m-d H:i:s');
			$res = $this->mensajeService->valida($this->mensaje);
			if ($res !== true)
			{
				$this->errores = $res;
				return 'error';
			}
			if (!$this->mensajeService->save($this->mensaje))
			{
				$this->error = 'No ha sido posible enviar el mensaje';
				return 'error';
			}
			return 'success';
		}
	}