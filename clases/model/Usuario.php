<?php
	class Usuario extends Model
	{
		protected $id_usuario;
		protected $nombre;
		protected $clave;
		protected $email;
		protected $mensajes_enviados;
		protected $mensajes_recibidos;
		protected $contactos;
		
		public function __construct($datos = null)
		{
			parent::__construct($datos);
			$this->pk['id_usuario'] = 'manual';
			$this->fk['mensajes_enviados'] = new FK('Mensaje', OneToMany, 'id_usuario_envia', null, 'fecha_envio desc');
			$this->fk['mensajes_recibidos'] = new FK('Mensaje', OneToMany, 'id_usuario_destino', null, 'fecha_envio desc');
			$this->fk['contactos'] = new FK('Usuario', ManyToMany, 'id_usuario', 'id_contacto', 'nombre', 'Contacto', 'id_usuario');
		}
	}