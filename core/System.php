<?php
	class System
	{
		protected static $db;
		public $conexion;
		
		function __construct()
		{
			$this->conexion=& $this->conectar_db();
			self::$db = new DataBase();
			self::$db->connect();
		}

		public function &conectar_db()
		{
			$db = new MysqliDb(
				Array(
					'host' => APP_DB_HOST,
					'username' => APP_DB_USER,
					'password' => APP_DB_PASSWORD,
					'db'=> APP_DB_CATALOG,
					'port' => APP_DB_PORT,
					'charset' => 'utf8'
				)
			);
			$db->autoReconnect = true;
			return $db;
		}

		function info_site($array,$busqueda)
		{return 0;//pendiente
			if(is_array($array))
			{
				foreach ($array as $key => $value)
				{
					if($value["nom"]==$busqueda)
					{
						return $array[$key]["val"];
					}
				}
			}
			else
			{
				return false;
			}
		}
		
		public function test()
		{
			return array("pasa"=>"creo que no","porque"=>"NPI");
		}
	}
?>
