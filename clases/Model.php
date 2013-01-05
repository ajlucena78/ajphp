<?php
	require_once('FK.php');
	
	abstract class Model
	{
		protected $propiedades;
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
				if ($propiedad == 'propiedades' or $propiedad == 'pk' or $propiedad == 'fk')
					continue;
				$this->propiedades[] = $propiedad;
				if (isset($datos) and is_array($datos) and isset($datos[$propiedad]))
					$this->$propiedad = $datos[$propiedad];
			}
		}
		
		private function get($atributo, $limite = null, $inicio = null)
		{
			if ($this->$atributo === null and isset($this->fk[$atributo]))
				$this->cargaRef($atributo, $limite, $inicio);
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
	    	if (isset($parametros[0]) and $parametros[0])
	    		$limite = $parametros[0];
	    	else
	    		$limite = null;
	    	if (isset($parametros[1]) and $parametros[1])
	    		$inicio = $parametros[1];
	    	else
	    		$inicio = null;
	    	if (isset($parametros[2]) and $parametros[2])
	    		$id = $parametros[2];
	    	else
	    		$id = null;
	    	return $this->get($atributo, $limite, $inicio, $id);
	    }
		
		protected function load()
		{
			foreach ($this->fk as $propiedad => $fk)
			{
				if (strtolower($fk->getRelation_type()) != OneToOne 
						and strtolower($fk->getRelation_type()) != ManyToOne)
					continue;
				if (!$this->cargaRef($propiedad))
					return false;
			}
		}
		
		protected function cargaRef($propiedad, $limite = null, $inicio = null, $index = null, $soloId = false
				, $indexPK = null)
		{
			if ($this->$propiedad !== null)
				return null;
			if (!isset($this->fk[$propiedad]))
				return false;
			$elementos = Service::cargaRef($this, $propiedad, $limite, $inicio, $index, $soloId, $indexPK);
			if ($elementos === false)
				return false;
			if ($this->fk[$propiedad]->getRelation_type() == ManyToOne)
			{
				if (isset($elementos[0]))
					$elementos = $elementos[0];
				else
					$elementos = array();
			}
			$this->$propiedad = $elementos;
			return true;
		}
		
		public function get_propiedades()
		{
			return $this->propiedades;
		}
		
		public function get_pk($pk = null)
		{
			if (!$pk)
				return $this->pk;
			if (isset($this->pk[$pk]))
				return $this->pk[$pk];
			return null;
		}
		
		public function get_fk($fk = null)
		{
			if (!$fk)
				return $this->fk;
			if (isset($this->fk[$fk]))
				return $this->fk[$fk];
			return null;
		}
	}