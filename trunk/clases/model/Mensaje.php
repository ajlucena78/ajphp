<?php
	class Mensaje extends Model
	{
		protected $id_mensaje;
		protected $usuario_envia;
		protected $usuario_destino;
		protected $mensaje;
		protected $fecha_envio;
		
		public function __construct($datos = null)
		{
			parent::__construct($datos);
			$this->pk['id_mensaje'] = 'manual';
			$this->fk['usuario_envia'] = new FK('Usuario', ManyToOne, 'id_usuario_envia');
			$this->fk['usuario_destino'] = new FK('Usuario', ManyToOne, 'id_usuario_destino');
		}
	}