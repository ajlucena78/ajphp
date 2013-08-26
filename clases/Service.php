<?php
	require_once APP_ROOT . '/clases/Conexion.php';
	
	abstract class Service
	{
		protected static $conexion;
		private $model;
		protected $error;
		
		public function Service()
		{
			if (!self::$conexion)
			{
				self::$conexion = new Conexion();
				if (!self::$conexion->conecta(DB_URL, DB_USERNAME, DB_PASSWORD))
					$this->error = self::$conexion->error();
			}
			require_once(APP_ROOT . '/clases/model/' . str_replace('Service', '', get_class($this)) . '.php');
			$clase = str_replace('Service', '', get_class($this));
			$this->model = new $clase();
		}
		
		public static function cargaService($service)
		{
			$res = new $service();
			return $res;
		}
		
		public function inicia_transaccion()
		{
			return self::$conexion->inicia_transaccion();
		}
		
		public function cierra_transaccion()
		{
			return self::$conexion->cierra_transaccion();
		}
		
		public function error()
		{
			return $this->error;
		}
		
		public static function cargaRef(Model $model, $propiedad, $limite = null, $inicio = 0
				, $index = null, $soloId = false, $indexPK = null)
		{
			foreach ($model->pk as $pk => $tipo);
			$id = $model->$pk;
			if (!$id)
				return false;
			$fk = $model->fk($propiedad);
			if (!$fk)
				return false;
			if ($fk->index() and !$index)
				$index = $fk->index();
			if ($soloId and $index)
			{
				//TODO
				$sql = 'select ';
				if (is_array($index) and count($index) > 0)
				{
					$cont = 0;
					foreach ($index as $ind)
					{
						if ($cont++)
							$sql .= ', ';
						$sql .= $ind;
					}
				}
				else
					$sql .= $index;
				if ($indexPK)
					$sql .= ', ' . $indexPK;
				$sql .= ' from ' . $fk->model() . ' where ' . $fk->link_model() . ' = \'' . $id . '\'';
			}
			else
			{
				$sql = 'select distinct d1.* from ' . $fk->model() . ' d1';
				if ($fk->relation_type() == ManyToMany)
				{
					$sql .= ' join ' . $fk->model_relational() . ' d2 on (d2.' . $fk->link_model();
					$sql .= ' = \'' . $id . '\' and d2.' . $fk->link_external_model() . ' = d1.' . $pk . ')';
				}
				elseif ($fk->relation_type() == OneToMany)
				{
					$sql .= ' where d1.' . $fk->link_model() . ' = \'' . $id . '\'';
				}
				elseif ($fk->relation_type() == ManyToOne)
				{
					$modelExternalClass = $fk->model();
					$modelExternal = new $modelExternalClass();
					foreach ($modelExternal->pk as $pk2 => $tipo);
					$modelExternal = null;
					$sql .= ' join ' . get_class($model) . ' d2 on (d2.' . $fk->link_model() . ' = d1.' 
							. $pk2 . ' and d2.' . $pk . ' = \'' . $id . '\')';
				}
				if ($fk->order())
				{
					$sql .= ' order by ';
					if (!is_array($fk->order()))
						$sql .= 'd1.' . $fk->order();
					else
					{
						$cont = 1;
						foreach ($fk->order() as $key)
						{
							$sql .= 'd1.' . $key;
							if ($cont++ < count($ref[7]))
								$sql .= ', ';
						}
					}
				}
			}
			if ($limite)
			{
				$sql .= ' limit';
				if ($inicio)
					$sql .= ' ' . $inicio . ',';
				$sql .= ' ' . $limite;
			}
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
				return false;
			$registros = array();
			if ($soloId and $index)
			{
				if (!$indexPK)
					$indexPK = $index;
				while ($registro = $consulta->lee_registro())
				{
					if (is_array($index) and count($index) > 0)
					{
						//para cada elemento del array 'index' se crea una dimensión en los valores devueltos
						$orden = '$registros';
						foreach ($index as $ind)
							$orden .= '[$registro[\'' . $ind . '\']]';
						$orden .= ' = $registro[\'' . $ind . '\'];';
						eval ($orden);
					}
					else
						$registros[$registro[$index]] = $registro[$indexPK];
				}
			}
			else
			{
				$fk_model = $fk->model();
				require_once(APP_ROOT . '/clases/model/' . $fk_model . '.php');
				while ($registro = $consulta->lee_registro())
				{
					if ($index)
					{
						if (is_array($index) and count($index) > 0)
						{
							//TODO
							/*
							Para cada elemento del array 'index' se crea una dimensión en los valores  
							devueltos
							*/
							$orden = '$registros';
							foreach ($index as $ind)
								$orden .= '[$registro[\'' . $ind . '\']]';
							$orden .= ' = new $fk_model($registro);';
							eval ($orden);
						}
						else
							$registros[$registro[$index]] = new $fk_model($registro);
					}
					else
						$registros[] = new $fk_model($registro);
				}
			}
			$consulta->libera();
			return $registros;
		}
		
		public function findAll($order = null, $index = null)
		{
			$sql = 'select * from ' . get_class($this->model);
			if ($order)
			{
				$sql .= ' order by ';
				$cont = 1;
				foreach ($order as $key)
				{
					$sql .= $key;
					if ($cont++ < count($order))
						$sql .= ', ';
				}
			}
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			$registros = array();
			while ($registro = $consulta->lee_registro())
			{
				$clase = get_class($this->model);
				if ($index)
					$registros[$registro[$index]] = new $clase($registro);
				else
					$registros[] = new $clase($registro);
			}
			$consulta->libera();
			return $registros;
		}
		
		public function find($model, $max = null, $order = null, $likes = null, $excludes = null, $total = false
				, $inicio = 0, $listaId = null)
		{
			if ($total)
				$sql = 'select count(*) as total';
			else
				$sql = 'select *';
			$sql .= ' from ' . get_class($this->model);
			$sql .= ' where true';
			foreach ($model->propiedades() as $nombre)
			{
				$campo = null;
				if ($fk = $model->fk($nombre))
				{
					if ($fk->relation_type() == ManyToOne)
						$campo = $fk->link_model();
				}
				else
					$campo = $nombre;
				if ($campo)
				{
					$valor = $model->$nombre;
					if (!is_null($valor) and !($valor === ''))
					{
						if ($fk and $fk->relation_type() == ManyToOne)
						{
							if ($valor == 'null')
							{
								$sql .= ' and ' . $fk->link_model() . ' is null';
								continue;
							}
							if (!is_object($valor))
								continue;
							if (!in_array($campo, $model->propiedades()))
								$metodo = $fk->link_external_model();
							else
								$metodo = $campo;
							$valor = $valor->$metodo;
							if ($valor === null)
								continue;
						}
						if (isset($likes[$nombre]))
						{
							$sql .= ' and ' . $campo . ' like ';
							$valor = '%' . $valor . '%';
						}
						elseif ($valor == 'null')
						{
							$sql .= ' and ' . $campo . ' is null';
						}
						else
						{
							$sql .= ' and ' . $campo . ' = ';
							$sql .= "'" . str_replace("'", "\'", $valor) . "'";
						}
					}
				}
			}
			if (is_array($excludes) and count($excludes) > 0)
			{
				foreach ($model->pk() as $pk => $tipo);
				$sql .= ' and ' . $pk . ' not in (';
				$cont = 1;
				foreach ($excludes as $id)
				{
					$sql .= '\'' . $id . '\'';
					if ($cont < count($excludes))
						$sql .= ', ';
					$cont++;
				}
				$sql .= ')';
			}
			if ($listaId)
			{
				foreach ($model->pk() as $pk => $tipo);
				$sql .= ' and ' . $pk . ' in (';
				$cont = 0;
				foreach ($listaId as $id)
				{
					if ($cont++)
						$sql .= ', ';
					$sql .= '\'' . $id . '\'';
				}
				$sql .= ')';
			}
			if ($order and !$total)
			{
				if (!is_array($order))
					$order = array($order);
				$sql .= ' order by ';
				$cont = 0;
				foreach ($order as $key)
				{
					if ($cont++)
						$sql .= ', ';
					$sql .= $key;
				}
			}
			if ($max)
				$sql .= ' limit ' . $inicio . ', ' . $max;
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			if ($total)
			{
				if (!$registro = $consulta->lee_registro())
					return false;
				$res = $registro['total'];
			}
			else
			{
				$res = array();
				while ($registro = $consulta->lee_registro())
				{
					$clase = get_class($this->model);
					$res[] = new $clase($registro);
				}
			}
			$consulta->libera();
			return $res;
		}
		
		public function total()
		{
			$sql = 'select count(*) as total from ' . get_class($this->model);
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			if ($registro = $consulta->lee_registro())
				$total = $registro['total'];
			else
				$total = 0;
			$consulta->libera();
			return $total;
		}
		
		public function save($model, $update = false)
		{
			if (!$update)
			{
				//nuevo
				$sql = 'insert into ' . get_class($this->model) . ' (';
				$cont = 0;
				$sql2 = '';
				foreach ($model->propiedades() as $nombre)
				{
					if ($model->pk($nombre) == 'auto')
						continue;
					$valor = $model->$nombre();
					if ($fk = $model->fk($nombre))
					{
						if ($fk->relation_type() == ManyToOne or $fk->relation_type() == OneToOne)
						{
							if ($cont > 0) $sql .= ', ';
							$sql .= $fk->link_model();
						}
					}
					else
					{
						if ($cont > 0) $sql .= ', ';
						$sql .= $nombre;
					}
					if ($fk = $model->fk($nombre))
					{
						if (($fk->relation_type() == ManyToOne or $fk->relation_type() == OneToOne) 
								and $valor != null)
						{
							//$submetodo = $fk->link_external_model();
							foreach ($valor->pk() as $pk2 => $tipo)
							$valor = $valor->$pk2;
						}
					}
					if (!$fk or ($fk->relation_type() == ManyToOne or $fk->relation_type() == OneToOne))
					{
						if ($cont++ > 0) $sql2 .= ', ';
						if ($valor === null or $valor === 'null' or $valor === array())
							$sql2 .= 'null';
						elseif ($valor === true)
							$sql2 .= 'true';
						elseif ($valor === false)
							$sql2 .= 'false';
						else
							$sql2 .= "'" . $valor . "'";
					}
				}
				$sql .= ') values (' . $sql2 . ')';
			}
			else
			{
				//actualización
				$sql = 'update ' . get_class($this->model) . ' set ';
				$cont = 0;
				foreach ($model->propiedades() as $nombre)
				{
					if ($model->pk($nombre) == 'auto')
						continue;
					$valor = $model->$nombre;
					if ($fk = $model->fk($nombre))
					{
						if ($fk->relation_type() == ManyToOne)
						{
							if ($cont > 0) $sql .= ', ';
							$sql .= $fk->relation_type() . ' = ';
						}
					}
					else
					{
						if ($cont++ > 0) $sql .= ', ';
						$sql .= $nombre . ' = ';	
					}
					if ($fk = $model->fk($nombre))
					{
						if ($fk->relation_type() == ManyToOne and $valor != null)
						{
							$submetodo = $fk->link_external_model();
							$valor = $valor->$submetodo;
						}
					}
					if (!$fk or $fk->relation_type() == ManyToOne)
					{
						if ($valor === null or $valor === 'null' or $valor === array())
							$sql .= 'null';
						elseif ($valor === true)
							$sql .= 'true';
						elseif ($valor === false)
							$sql .= 'false';
						else
							$sql .= "'" . $valor . "'";
					}
				}
				$sql .= ' where true';
				foreach ($model->pk() as $pk => $tipo)
				{
					$sql .= ' and ' . $pk . " = '" . $model->$pk . "'";
				}
			}
			$res = self::$conexion->ejecuta($sql);
			if ($res === false)
			{
				$this->error = self::$conexion->error();
				return false;
			}
			return $res;
		}
		
		public function last_insert_id()
		{
			//obtiene el id si se ha generado
			$sql = 'select last_insert_id() as id from ' . get_class($this->model);
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			$id = null;
			if ($registro = $consulta->lee_registro())
				$id = $registro['id'];
			return $id;
		}
		
		public function findById($id)
		{
			$clase = get_class($this->model);
			$sql = 'select * from ' . $clase . ' model';
			//hereda de otra clase padre?
			$clasePadre = get_parent_class($this->model);
			if ($clasePadre)
			{
				foreach ($this->model->pk() as $pk => $tipo)
				$sql .= ' inner join ' . $clasePadre . ' padre on (padre.' . $pk . ' = \'' . $id . '\')';
			}
			$sql .= ' where true';
			foreach ($this->model->pk() as $pk => $tipo)
				$sql .= ' and model.' . $pk . ' = \'' . $id . '\'';
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			$model = null;
			if ($registro = $consulta->lee_registro())
				$model = new $clase($registro);
			$consulta->libera();
			return $model;
		}
		
		public function removeById($id)
		{
			if (!$res = $this->findById($id))
			{
				if ($res === null)
					$this->error = 'Elemento de tipo ' . get_class($this->model) . ' no lozalido para ' . $id;
				return $res;
			}
			$sql = "delete from " . get_class($this->model) . ' where true';
			foreach ($this->model->pk() as $pk => $tipo)
				$sql .= ' and ' . $pk . ' = \'' . $id . '\'';
			$consulta = new Consulta(self::$conexion);
			if (!$consulta->ejecuta($sql))
			{
				$this->error = $consulta->error();
				return false;
			}
			return true;
		}
		
		public function save_relation($model, $relacion)
		{
			if (!$fk = $model->fk($relacion))
				return false;
			foreach ($model->pk() as $pk => $tipo);
			$id = $model->$pk;
			if (!$id)
				return false;
			if (!is_array($model->$relacion) or count($model->$relacion) == 0)
				return true;
			$metodo2 = $fk->link_model();
			foreach ($model->$relacion as $elemento)
			{
				$id2 = $elemento->$metodo2();
				if (!$id2)
					return false;
				if ($fk->link_model() == $pk)
					$pk2 = $fk->link_external_model();
				else
					$pk2 = $fk->link_model();
				$sql = 'insert into ' . $fk->model_relational() . ' (' . $pk . ', ' . $pk2 . ')';
				$sql .= ' values (\'' . $id . '\', \'' . $id2 . '\')';
				if (!$res = self::$conexion->ejecuta($sql))
				{
					$this->error = self::$conexion->error();
					return false;
				}
			}
			return true;
		}
		
		public function destroy_relation($model, $relacion)
		{
			if (!$fk = $model->fk($relacion))
				return false;
			foreach ($model->pk() as $pk => $tipo);
			$id = $model->$pk;
			if (!$id)
				return false;
			$metodo2 = $fk->link_model();
			foreach ($model->$relacion as $elemento)
			{
				$id2 = $elemento->$metodo2();
				if (!$id2)
					return false;
				if ($fk->link_model() == $pk)
					$pk2 = $fk->link_external_model();
				else
					$pk2 = $fk->link_model();
				$sql = 'delete from ' . $fk->model_relational() . ' where ' . $pk . ' = \'' . $id 
						. '\' and ' . $pk2 . ' = \'' . $id2 . '\'';
				if (!$res = self::$conexion->ejecuta($sql))
				{
					$this->error = self::$conexion->error();
					return false;
				}
			}
			return true;
		}
		
		public function exist_relation($model, $relacion)
		{
			if (!$fk = $model->fk($relacion))
				return false;
			foreach ($model->pk() as $pk => $tipo);
			$id = $model->$pk;
			if (!$id)
				return false;
			if (!is_array($model->$relacion) or count($model->$relacion) == 0)
				return true;
			$metodo2 = $fk->link_model();
			foreach ($model->$relacion as $elemento)
			{
				$id2 = $elemento->$metodo2();
				if (!$id2)
					return false;
				if ($fk->link_model() == $pk)
					$pk2 = $fk->link_external_model();
				else
					$pk2 = $fk->link_model();
				$sql = 'select * from ' . $fk->model_relational() . ' where ' . $pk . ' = \'' . $id 
						. '\' and ' . $pk2 . ' = \'' . $id2 . '\'';
				$consulta = new Consulta(self::$conexion);
				if (!$consulta->ejecuta($sql))
					return false;
				$registro = $consulta->lee_registro();
				if (!$registro)
					return false;
				return true;
			}
			return true;
		}
	}