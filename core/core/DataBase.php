<?php
#===================================================#
#	 coded by: Moises Espindola
#	 nick: zaer00t
#	e-mail: zaer00t@gmail.com
#	www: http://nope
#	date: 12/Septiembre/2012
#	code name: bluedeb.com
#==================================================#

class DataBase {

	/** @var mysqli */
	private $drv;
	private $consulta;
	private $error;

	/*
		Metodo para realizar la conexion a una base de datos, los parametros son opcionales en caso de
		requerir una conexion a otra DB y otro puerto. Al finalizar el manejador se queda en la variable
		@drv la cual es utilizada en todo el proceso de vida de la conexion
	*/
	function connect($host=APP_DB_HOST, $user=APP_DB_USER, $pwd=APP_DB_PASSWORD, $database=APP_DB_CATALOG)
	{
		if(defined("APP_DB_PORT"))
		{
			$this->drv = new mysqli($host, $user, $pwd, $database,APP_DB_PORT);
		}
		else
		{
			$this->drv = new mysqli($host, $user, $pwd, $database);
		}

		$this->utf8Mode();

		if($this->drv->connect_errno)
		{
			$this->error = $this->drv->mysqli->connect_errno;
			return false;
		}
		else
		{
			return $this->getError();
		}
	}

	/*
		En caso de generar algun error durante las solicitudes, los mensajes de error
		son almacenados en la variable privada error
	*/

	public function getError()
	{
		return $this->error;
	}
	function disconnect() {
		$this->drv->close();
	}

	function __destruct() {
		$this->disconnect();
	}

	public function utf8Mode() {
		$this->drv->query("SET NAMES 'utf8'");
	}

	/*
		Obtiene la primera letra del tipo de dato de la variable
		i.e.
		integer devuelve 'i'
		string devuelve 's'
		et...
	*/
	private function getTypeString($data)
	{
		$str = "";
		foreach($data as $value)
		{
			$x=gettype($value);
			$str .= substr($x,0,1);
			#$str .= substr(gettype($value), 0, 1);
		}
		return $str;
	}

	private function refValues($arr)
	{
	   	#Util::debug($arr,'Revisar refValues 1',1,1,1);
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array(); $x=0;
			foreach($arr as $key => $value)
			{
				if($x==10)
				$refs[$key] = &$arr[$key];
				else
					$refs[$key] = &$arr[$key];
				$x++;
			}
			#Util::debug($refs,'Revisar REFS',1,1,1);
			return $refs;
		}
		#Util::debug($arr,1,0,'Revisar refValues 2');
		return $arr;
	}

	private function bindParam($stmt, $data)
	{
		if($stmt===false)
		{
			Util::debug($stmt,__METHOD__.":".__LINE__,1,1,1);
		}
		else
		{
			#Util::debug($stmt,"Checando STMT");
			#Util::debug($data,"DATA",1,1,1);
			$datos=$this->refValues(
				array_merge(
					array(
						$this->getTypeString($data)
					),
					$data)
			);
			#Util::debug($datos,"Revisando datos",1,1,1);
			if(!(call_user_func_array(array($stmt, "bind_param"),$datos)))
			{
				#throw new Exception("Error durante binding MySQL");
				echo "La consulta: ".$this->consulta;
				throw new Exception("<br><b>Archivo:</b> ".__FILE__."<br>Linea:".__LINE__."<br>Metodo: ".__METHOD__."<br>Error: <span style='color:crimson'>".mysqli_connect_error()."</span>(Error bind param)");
			}
		}
	}

	private function fetchObject($stmt)
	{
		$meta = $stmt->result_metadata();
		$fields = $meta->fetch_fields();

		foreach($fields as $field)
		{
			$result[$field->name] = "";
			$resultArray[$field->name] = &$result[$field->name];
		}
		$rows = null;

		call_user_func_array(array($stmt, 'bind_result'), $resultArray);
		while($stmt->fetch())
		{
			$resultObject = new stdClass();
			foreach ($resultArray as $key => $value)
			{
				$resultObject->$key = $value;
			}
			$rows[] = $resultObject;
		}
		return $rows;
	}

	private function fetchArray($stmt)
	{
		$meta = $stmt->result_metadata();
		$fields = $meta->fetch_fields();

		foreach($fields as $field)
		{
			$result[$field->name] = "";
			$resultArray[$field->name] = &$result[$field->name];
		}

		$rows = null;
		call_user_func_array(array($stmt, 'bind_result'), $resultArray);

		while($stmt->fetch())
		{
			$arr = array();
			foreach ($resultArray as $key => $value)
			{
				$arr[$key] = $value;
			}

			$rows[] = $arr;
		}

		return $rows;
	}

	function insert($table, $data)
	{
		$keys = array_keys($data);
		$query = "INSERT INTO " . $this->drv->escape_string($table) . " (";
		$query2 = ") VALUES (";
		for ($i = 0; $i < count($keys); $i++)
		{
			$query .= $keys[$i];
			$query2 .= "?";

			if ($i < count($keys)-1) {
				$query .= ",";
				$query2 .= ",";
			}
		}

		$query2 .= ")";

		$stmt = $this->drv->prepare($query . $query2);

		$this->bindParam($stmt, $data);
		if(!$stmt->execute())
		{
			$error = current($stmt->error_list);
			return $error;
			#throw new Exception("Mysql (".$stmt->errno.") ".$query.$query2);
		}

		$id = $stmt->insert_id;
		$stmt->close();
		return $id;
	}

	function select($table, $fields, $where, $data)
	{
		$this->consulta=$query="SELECT ".$this->drv->escape_string($fields)." FROM ".$this->drv->escape_string($table)." WHERE ".$where;

		if($stmt = $this->drv->prepare($query))
		{
			$this->bindParam($stmt,$data);
			if(!$stmt->execute())
			{
				#throw new Exception("Error Mysql (" . mysqli_ . ")");
				throw new Exception("<br><b>Archivo:</b> ".__FILE__."<br>Linea:".__LINE__."<br>Metodo: ".__METHOD__."<br>Error: <span style='color:crimson'>".mysqli_connect_error()."</span>(SELECT)");
				#return 0;
			}
			$result = $this->fetchArray($stmt);
			$stmt->close();
			return $result;
		}
		else
		{
			// retorna FALSE porque la consulta sigue siendo un asco o no se que madres pasa...
			return false;
		}
	}

	function update($table, $data, $where, $data2)
	{
		$query = "UPDATE " . $this->drv->escape_string($table) . " SET ";
		$keys = array_keys($data);

		for($i=0; $i<count($keys); $i++){
			$query .= $keys[$i] . "=?";
			if($i < count($keys)-1){
				$query .= ",";
			}
		}

		$query .= " WHERE " . $where;
		$this->consulta=$query;
		
		$stmt = $this->drv->prepare($query);
		if($stmt===false)
		{
			throw new Exception("Error Mysql (" . $this->drv->error . " : ".$this->drv->errno." )");
		}
		#Util::debug(array_merge($data, $data2),"Array_merge");
		$this->bindParam($stmt, array_merge($data, $data2));
		if(!$stmt->execute()) {
			//throw new Exception("Error Mysql (" . $stmt->error . ")");
			return $stmt->error;
		}

		$rows = $stmt->affected_rows;
		$stmt->close();
		return $rows;
	}

	function updateIncField($table, $field, $where, $data) {
		$field = $this->drv->escape_string($field);
		$query = "UPDATE " . $this->drv->escape_string($table) . " SET " . $field . "=" . $field . "+1 WHERE " . $where;

		 $stmt = $this->drv->prepare($query);

		$this->bindParam($stmt, $data);

		if(!$stmt->execute()) {
			throw new Exception("Error Mysql (" . $stmt->errno . ")");
		}

		$rows = $stmt->affected_rows;
		$stmt->close();
		return $rows;
	}

	function delete($table, $where, $data)
	{
		$query = "DELETE FROM ".$this->drv->escape_string($table)." WHERE ".$where;
		$stmt = $this->drv->prepare($query);
		$this->bindParam($stmt, $data);

		if(!$stmt->execute())
		{
			#throw new Exception("Error Mysql (" . $stmt->errno . ")");
			return false;
		}
		$rows = $stmt->affected_rows;
		$stmt->close();
		return $rows;
	}

	public function execute($sql, $data = null, $returnValue = 0)
	{
		#Util::debug(func_get_args(),"parametros de la funcion",1,1,1);
		$statement = $this->drv->prepare($sql);
		$statement->execute();
		return $statement;
	}

	public function fArray($stmt)
	{
		$meta = $stmt->result_metadata();
		$fields = $meta->fetch_fields();

		foreach($fields as $field)
		{
			$result[$field->name] = "";
			$resultArray[$field->name] = &$result[$field->name];
		}

		$rows = null;

		call_user_func_array(array($stmt, 'bind_result'), $resultArray);
		while($stmt->fetch())
		{
			$arr = array();

			foreach ($resultArray as $key => $value)
			{
				$arr[$key] = $value;
			}
			$rows[] = $arr;
		}
		return $rows;
	}

	public function laConsulta()
	{
		echo "Resultado de la consulta: ";
		return "<pre>".$this->consulta."</pre>";
	}
}

?>
