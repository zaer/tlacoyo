<?php
#==================================================#
#     coded by: Moises Espindola                   #
#     nick: zaer00t                                #
#    e-mail: zaer00t@gmail.com                     #
#    www: http://www.alfa-gps.com                  #
#    date: 22/07/2021                              #
#    code name:                                    #
#==================================================#

	class Info_site
	{
		
		private $db;
		private $nom;
		private $val;

		function __construct($db) { $this->db = $db; }

		private static function crear($db,$nom,$val)
		{
			$dato = array();
			$dato["nom"]=$nom;
			$dato["val"]=$val;
			return $db->insert("info_site",$dato);
		}

		public static function nuevo($db,$nom,$val)
		{
			return Info_site::crear($db,$nom,$val);
		}

		public function leer($id)
		{
			$id=(int)$id;
			$r1 = $this->db->select("info_site","id,nom,val", "id=?",array($id));
			if($r1!=null)
			{
				$r1=$r1[0];
				$this->id=$id;
				$this->nom=$r1["nom"];
				$this->val=$r1["val"];
				}
			else
			{
				return false;
			}
		}
		

		public static function leer_todos($db)
		{
			$r1 = $db->select("info_site", "id", "id>? order by id asc", array(0));
			$datos = array();
			if (count($r1) > 0)
			{
				foreach($r1 as $id_dato)
				{
					$entrada = new Info_site($db);
					$entrada->leer($id_dato["id"]);
					$datos[] = $entrada;
				}
			}
			return $datos;
		}

		public function actualizar()
		{
			$dato = array();
			$dato["nom"]=$this->nom;
			$dato["val"]=$this->val;
			return $this->db->update("info_site",$dato,"id=?",array($this->id));
		}

		

		public function borrar()
		{
			return $this->db->delete("info_site","id=?", array($this->id));
		}



		/** setters **/
		public function setNom($dato){ $this->nom=$dato; }
		public function setVal($dato){ $this->val=$dato; }

		/** getters **/
		public function getNom(){ return $this->nom; }
		public function getVal(){ return $this->val; }

		/** end scaffolding **/
	}
?>