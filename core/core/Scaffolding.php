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


	/*	Scaffolding.php
	 *	Clase para generar el CRUD para casi cualquier tabla
	 *	Autor: Moises Espindola Oropeza
	 *	email: zaer00t@gmail.com
	 *	url: creasati.com.mx
	 *	Codigo que crea Codigo LOL
	 */
	class Scaffolding
	{
		private $db;
		private $tabla;
		private $columnas;
		private $metodo;

		function __construct($db,$tabla)
		{
			$this->db = $db;
			$this->tabla=$tabla;
			$this->getColumnas();
			$this->metodo=ucfirst($tabla);
		}

		private function getColumnas()
		{
			$sql = "show full columns from ".$this->tabla;
			$stmt = $this->db->execute($sql);
			$result = $this->db->fArray($stmt);
			$stmt->close();
			$data=array();
			foreach($result as $field)
			{
				$data[]=$field["Field"];
			}
			$this->columnas = $data;
		}

		private function header()
		{
			$fecha = date("d/m/Y",time());
			$head = "\n#==================================================#\n#     coded by: Moises Espindola                   #\n#     nick: zaer00t                                #\n#    e-mail: zaer00t@gmail.com                     #\n#    www: http://www.bluedeb.com                   #\n#    date: {$fecha}                              #\n#    code name: {$this->tabla}                                   #\n#==================================================#\n";
			return $head;
		}

		private function setAtributos()
		{
			$atributos="\n\t\tprivate \$db;";
			foreach($this->columnas as $field)
			{
				$atributos.="\n\t\tprivate \${$field};";
			}
			return $atributos."\n\n\t\t";
		}

		public function getters()
		{
			$data=array();
			foreach($this->columnas as $field)
			{
				$tmp = ucfirst($field);
				$data[]="public function get{$tmp}(){ return \$this->{$field}; }";
			}
			return $data;
		}
		public function setters()
		{
			$data=array();
			foreach($this->columnas as $field)
			{
				$tmp = ucfirst($field);
				$data[] = "public function set{$tmp}(\$dato){ \$this->{$field}=\$dato; }";
			}
			return $data;
		}

		public function metCrear()
		{
			$cabecera = "private static function crear(\$db";
			$parametros ="";
			$contenido="\n\t\t{\n\t\t\t\$dato = array();\n\t\t\t";
			foreach($this->columnas as $field)
			{
				if($field!='id'.$this->tabla)
				{
					$parametros.=",\${$field}";
					$contenido.="\$dato[\"{$field}\"]=\${$field};\n\t\t\t";
				}
				else if($field!='id'.$this->tabla){
					$parametros.=",\${$field}";
					$contenido.="\$dato[\"{$field}\"]=\${$field};\n\t\t\t";
				}
			}
			$pie="return \$db->insert(\"{$this->tabla}\",\$dato);";
			return $cabecera .= $parametros.")".$contenido.$pie."\n\t\t}\n\n\t\t";
		}

		public function metNuevo()
		{
			$cabecera = "public static function nuevo(\$db";
			$parametros ="";
			$contenido="return {$this->metodo}::crear(\$db";
			foreach($this->columnas as $field)
			{
				if($field!='id'.$this->tabla)
				{
					$parametros.=",\${$field}";
				}
			}
			return $cabecera .= $parametros.")\n\t\t{\n\t\t\t".$contenido.$parametros.");\n\t\t}\n\n\t\t";
		}

		public function metLeer()
		{
			/** creamos el metodo leer **/
			$cabecera="public function leer(\$id".$this->tabla.")";
			$contenido = "\n\t\t{\n\t\t\t\$id".$this->tabla."=(int)\$id".$this->tabla.";\n\t\t\t";
			$parametros ="\$r1 = \$this->db->select(\"$this->tabla\",\"id".$this->tabla."";
			$datos = "if(\$r1!=null)\n\t\t\t{\n\t\t\t\t\$r1=\$r1[0];\n\t\t\t\t\$this->id".$this->tabla."=\$id".$this->tabla.";\n\t\t\t\t";
			foreach($this->columnas as $field)
			{
				if($field != 'id'.$this->tabla)
				{
					$parametros.=",{$field}";
					$datos .= "\$this->{$field}=\$r1[\"{$field}\"];\n\t\t\t\t";
				}
			}
			$datos .= "}\n\t\t\telse\n\t\t\t{\n\t\t\t\treturn false;\n\t\t\t}\n\t\t}\n\t\t\n\n";
			$parametros.="\", \"id".$this->tabla."=?\",array(\$id".$this->tabla."));\n\t\t\t";
			$code = $cabecera.$contenido.$parametros.$datos;
			return $code;
		}

		public function metLeerTodos()
		{
			$cabecera="\t\tpublic static function leer_todos(\$db)\n\t\t{\n\t\t\t";
			$select = "\$r1 = \$db->select(\"{$this->tabla}\", \"id".$this->tabla."\", \"id".$this->tabla.">? order by id".$this->tabla." asc\", array(0));";
			$contenido = "\n\t\t\t\$datos = array();\n\t\t\t".
			"if (count(\$r1) > 0)\n\t\t\t".
			"{\n\t\t\t\t".
			"foreach(\$r1 as \$id_dato)\n\t\t\t\t".
			"{\n\t\t\t\t\t".
			"\$entrada = new {$this->metodo}(\$db);\n\t\t\t\t\t".
			"\$entrada->leer(\$id_dato[\"id".$this->tabla."\"]);\n\t\t\t\t\t".
			"\$datos[] = \$entrada;\n\t\t\t\t}\n\t\t\t}\n\t\t\treturn \$datos;\n\t\t}";
			return $cabecera.$select.$contenido;
		}
		
		public function metUpdate()
		{
			$cabecera = "\n\n\t\tpublic function actualizar()\n\t\t{\n\t\t\t";
			$contenido="\$dato = array();\n\t\t\t";
			foreach($this->columnas as $field)
			{
				if($field!='id'.$this->tabla)
				{
					$contenido.="\$dato[\"{$field}\"]=\$this->{$field};\n\t\t\t";
				}
			}

			$pie="return \$this->db->update(\"{$this->tabla}\",\$dato,\"id".$this->tabla."=?\",array(\$this->id".$this->tabla."));";
			return $cabecera.$contenido.$pie."\n\t\t}\n\n\t\t";

		}

		public function metBorrar()
		{
			$delete="\n\n\t\tpublic function borrar()\n\t\t{\n\t\t\treturn \$this->db->delete(\"{$this->tabla}\",\"id".$this->tabla."=?\", array(\$this->id".$this->tabla."));\n\t\t}\n\n";
			return $delete;
		}

		public function buildMetodo()
		{
			//encabezado del metodo 
			$ruta_metodo = APP_BIN_PATH."/Models/".ucfirst($this->metodo).".php";
			if(file_exists($ruta_metodo)==FALSE)
			{
				$nombre_metodo = ucfirst($this->metodo);
				$head = $this->header();
				$encabezado = "<?php{$head}\n\tclass {$nombre_metodo}\n\t{\n\t\t";
				$f1 = fopen($ruta_metodo,"w");
				if($f1==NULL) return 0;
				fprintf($f1,"%s",$encabezado);
	
				//atributos
				$atributos = $this->setAtributos();
				fprintf($f1,"%s",$atributos);
				//constructor
				$constructor="function __construct(\$db) { \$this->db = \$db; }\n\n\t\t";
				fprintf($f1,"%s",$constructor);
				//crud
				$metodo_crear = $this->metCrear();
				fprintf($f1,"%s",$metodo_crear);
				$metodo_nuevo = $this->metNuevo();
				fprintf($f1,"%s",$metodo_nuevo);
				$metodo_leer = $this->metLeer();
				fprintf($f1,"%s",$metodo_leer);
				$metodo_leer_todos = $this->metLeerTodos();
				fprintf($f1,"%s",$metodo_leer_todos);
				$metodo_update=$this->metUpdate();
				fprintf($f1,"%s",$metodo_update);
				$metodo_borrar=$this->metBorrar();
				fprintf($f1,"%s",$metodo_borrar);
	
				$setters = $this->setters();
				fprintf($f1,"\n\n\t\t/** setters **/");
				foreach($setters as $setter)
				{
					fprintf($f1,"\n\t\t%s",$setter);
				}
				$getters = $this->getters();
				fprintf($f1,"\n\n\t\t/** getters **/");
				foreach($getters as $getter)
				{
					fprintf($f1,"\n\t\t%s",$getter);
				}
				fprintf($f1,"\n\n\t\t/** end scaffolding **/\n\t}\n?>");
				fclose($f1);
				return 1;
			}
			else
			{
				return -1;
			}
		}
	}
?>
