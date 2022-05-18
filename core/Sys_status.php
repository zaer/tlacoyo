<?php
#===================================================#
#     coded by: Moises Espindola         _    _    #
#     nick: zaer00t                     | |  (_)   #
#    ___  _ __   ___   __ _  ___   __ _ | |_  _    #
#   / __|| '__| / _ \ / _` |/ __| / _` || __|| |   #
#  | (__ | |   |  __/| (_| |\__ \| (_| || |_ | |   #
#   \___||_|    \___| \__,_||___/ \__,_| \__||_|   #
#                                                  #
#    e-mail: zaer00t@gmail.com                     #
#    www: http://creasati.com.mx                   #
#    date: 12/Septiembre/2012                      #
#    code name: creasati.com.mx                    #
#==================================================#


	class Sys_status
	{
		
		private $db;
		private $id;
		private $nombre;

		function __construct($db) { $this->db = $db; }

		private static function crear($db,$nombre)
		{
			$dato = array();
			$dato["nombre"]=$nombre;
			return $db->insert("sys_status",$dato);
		}

		public static function nuevo($db,$nombre)
		{
			return Sys_status::crear($db,$nombre);
		}

		public function leer($id)
		{
			$id=(int)$id;
			$r1 = $this->db->select("sys_status","id,nombre", "id=?",array($id));
			if($r1!=null)
			{
				$r1=$r1[0];
				$this->id=$id;
				$this->nombre=$r1["nombre"];
				}
			else
			{
				return false;
			}
		}
		

		public static function leer_todos($db)
		{
			$r1 = $db->select("sys_status", "id", "id>? order by id asc", array(0));
			$datos = array();
			if (count($r1) > 0)
			{
				foreach($r1 as $id_dato)
				{
					$entrada = new Sys_status($db);
					$entrada->leer($id_dato["id"]);
					$datos[] = $entrada;
				}
			}
			return $datos;
		}

		private function actualizar()
		{
			$dato = array();
			$dato["nombre"]=$this->nombre;
			return $db->update("sys_status",$dato);
		}

		

		/** setters **/
		public function setId($dato){ $this->id=$dato; }
		public function setNombre($dato){ $this->nombre=$dato; }

		/** getters **/
		public function getId(){ return $this->id; }
		public function getNombre(){ return $this->nombre; }

		/** end scafolding **/
	}
?>
