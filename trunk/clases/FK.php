<?php
	define('ManyToOne', 'ManyToOne');
	define('OneToMany', 'OneToMany');
	define('OneToOne', 'OneToOne');
	define('ManyToMany', 'ManyToMany');
	
	class FK
	{
		private $model;
		private $link_model;
		private $link_external_model;
		private $relation_type;
		private $order;
		private $model_relational;
		private $index;
		
		public function __construct($model, $relation_type, $link_model, $link_external_model = null
				, $order = null, $model_relational = null, $index = null)
		{
			$this->model = $model;
			$this->link_model = $link_model;
			if ($link_external_model)
				$this->link_external_model = $link_external_model;
			else
				$this->link_external_model = $link_model;
			$this->relation_type = $relation_type;
			$this->order = $order;
			$this->model_relational = $model_relational;
			$this->index = $index;
		}
		
		public function getModel()
		{
			return $this->model;
		}
	
		public function getLink_model()
		{
			return $this->link_model;
		}
	
		public function getLink_external_model()
		{
			return $this->link_external_model;
		}
	
		public function getRelation_type()
		{
			return $this->relation_type;
		}
	
		public function getOrder()
		{
			return $this->order;
		}
		
		public function getModel_relational()
		{
			return $this->model_relational;
		}
		
		public function getIndex()
		{
			return $this->index;
		}
	}