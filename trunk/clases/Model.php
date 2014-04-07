<?php
	require_once('FK.php');
	
	abstract class Model
	{
		protected $propiedades;
		protected $propiedades_clase;
		protected $pk;
		protected $fk;
		
		public function __construct($datos = null)
		{
			$this->pk = array();
			$this->fk = array();
			$propiedades = get_class_vars(get_class($this));
			$this->propiedades = array();
			foreach ($propiedades as $propiedad => $valor)
			{
				if ($propiedad == 'propiedades' or $propiedad == 'propiedades_clase' or $propiedad == 'pk' 
						or $propiedad == 'fk')
					continue;
				$this->propiedades[$propiedad] = $propiedad;
				if (isset($datos) and is_array($datos) and isset($datos[$propiedad]))
					$this->$propiedad = $datos[$propiedad];
			}
			//propiedades de la clase hija, sin tener en cuenta la del padre si hay herencia
			$clasePadre = get_parent_class($this);
			if ($clasePadre != 'Model')
			{
				$propiedades = get_class_vars($clasePadre);
				$propiedadesPadre = array();
				foreach ($propiedades as $propiedad => $valor)
					$propiedadesPadre[$propiedad] = 1;
				$objClasePadre = new $clasePadre();
				$this->propiedades_clase = array();
				foreach ($this->propiedades as $propiedad)
				{
					if (!isset($propiedadesPadre[$propiedad]) or $objClasePadre->pk($propiedad))
						$this->propiedades_clase[$propiedad] = $propiedad;
				}
			}
			else
			{
				$this->propiedades_clase = $this->propiedades;
			}
		}
		
		private function get($atributo, $id = null, $limite = null, $inicio = null, $soloId = null)
		{
			if ($this->$atributo === null and isset($this->fk[$atributo]))
			{
				$this->cargaRef($atributo, $limite, $inicio, $soloId);
			}
			if ($id !== null)
			{
				$info = $this->$atributo;
				if (isset($info[$id]))
					return $info[$id];
				else
					return null;
			}
			else
				return $this->$atributo;
		}
		
		public function __get($atributo)
		{
			return $this->get($atributo);
		}
		
		public function __set($atributo, $valor)
	    {
	        $this->$atributo = $valor;
	    }
	    
	    public function __call($atributo, $parametros)
	    {
	    	if (!$atributo)
	    		return false;
	    	if (method_exists($this, $atributo))
	    		return $this->$atributo();
	    	if (isset($parametros[0]) and $parametros[0] !== null)
	    		$id = $parametros[0];
	    	else
	    		$id = null;
	    	if (isset($parametros[1]) and $parametros[1] !== null)
	    		$limite = $parametros[1];
	    	else
	    		$limite = null;
	    	if (isset($parametros[2]) and $parametros[2] !== null)
	    		$inicio = $parametros[2];
	    	else
	    		$inicio = null;
	    	if (isset($parametros[3]) and $parametros[3] === true)
	    		$soloId = true;
	    	else
	    		$soloId = null;
	    	return $this->get($atributo, $id, $limite, $inicio, $soloId);
	    }
		
		protected function load()
		{
			foreach ($this->fk as $propiedad => $fk)
			{
				if (strtolower($fk->relation_type()) != OneToOne 
						and strtolower($fk->relation_type()) != ManyToOne)
					continue;
				if (!$this->cargaRef($propiedad))
					return false;
			}
		}
		
		protected function cargaRef($propiedad, $limite = null, $inicio = null, $soloId = null)
		{
			if ($this->$propiedad !== null)
			{
				return null;
			}
			if (!isset($this->fk[$propiedad]))
			{
				return false;
			}
			$elementos = Service::cargaRef($this, $propiedad, $limite, $inicio, $soloId);
			if ($elementos === false)
			{
				return false;
			}
			if ($this->fk[$propiedad]->relation_type() == ManyToOne)
			{
				if (isset($elementos[0]))
					$elementos = $elementos[0];
				else
					$elementos = array();
			}
			$this->$propiedad = $elementos;
			return true;
		}
		
		public function propiedades()
		{
			return $this->propiedades;
		}
		
		public function propiedades_clase($id = null)
		{
			if ($id)
			{
				if (isset($this->propiedades_clase[$id]))
					return $this->propiedades_clase[$id];
				else
					return null;
			}
			else
				return $this->propiedades_clase;
		}
		
		public function pk($pk = null)
		{
			if (!$pk)
				return $this->pk;
			if (isset($this->pk[$pk]))
				return $this->pk[$pk];
			return null;
		}
		
		public function fk($fk = null)
		{
			if (!$fk)
				return $this->fk;
			if (isset($this->fk[$fk]))
				return $this->fk[$fk];
			return null;
		}
		
		public function data()
		{
			$datos = array();
			foreach ($this->propiedades as $propiedad)
			{
				$datos[$propiedad] = $this->$propiedad;
			}
			return $datos;
		}
	}