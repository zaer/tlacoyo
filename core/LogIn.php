<?php
#=================================================#
#    coded by: Moises Espindola                   #
#    nick: zaer00t                                #
#    e-mail: zaer00t@gmail.com                    #
#    www: http://creasati.com.mx                  #
#    date: 12/Septiembre/2012                     #
#    code name: creasati.com.mx                   #
#=================================================#
class LogIn
{
	private $db_connection = null;
	public $errors = array();
	public $messages = array();
	private $db;

	public function __construct($db)
	{
		$this->db=$db;
		session_start();

		if (isset($_GET["logout"]))
		{
			LoginAss::doLogout();
			Util::redirect(APP_HOST_URL.'/index');
		}
		elseif(isset($_POST["login"]))
		{
			$this->dologinWithPostData();
		}
	}
	/* funcion para verificar los permisos,
		verfica si las credenciales del cliente
		estan activas
		tengo(db,perm)
		@db handle a la base de datos
		@perm array de permisos
	*/
	static function tengo($db,$perm)
	{
		$login = new LoginAss($db);
		if(!LoginAss::isUserLoggedIn($perm)) //checamos permisos
		{
			Util::redirect(APP_HOST_URL."/cliente/logIn");
			//App::load_view("login",array(null));
		}
		else
		{
			return true;
		}
	}

	private function dologinWithPostData()
	{
		/* verificamos si los campos estan llenos */
		$cliente = $_POST["cliente"];
		$password =$_POST["password"];

		if($cliente=='')
		{
			$this->errors[]="Debes ingresar tu nombre de usuario";
			return 0;
		}
		else
		{
			if($password=='')
			{
				$this->errors[]="Debes ingresar tu contraseña";
				return 0;
			}
			else
			{
				// si todo va bien
				// verificamos si existe el cliente, por medio de su correo
				$db = new DataBase();
				$db->connect();
				$id_cte=Cliente::buscar_por_correo($db,$cliente);

				//si el puto cliente existe
				if($id_cte)
				{
					//leemos los datos del cliente
					$cte = new Cliente($db);
					$cte->leer($id_cte);
					//buscamos si el  cliente esta activo o no
					if($cte->getActivo()==0)
					{
						//el cliente no esta activo
						$this->errors[]="Lo sentimos, no puede utilizar su cuenta debido a que aun no ha sido activada";
						return 0;
					}
					else
					{
						//el cliente esta activo
						if($cte->getPassword()!='')
						{
							//el cliente tiene password
							// entonces verificamos el password
							if(Password::password_verify($password,$cte->getPassword()))
							{
								//el password es correcto
								/* la verificacion del password paso :D */
								$_SESSION["cliente"]['nombre']=$cte->getNombre();								$_SESSION["cliente"]['status']=$cte->getCorreo_electronico();
								$_SESSION["cliente"]["id_cliente"]=$cte->getId();
								Util::redirect(APP_HOST_URL."/cliente");
							}
							else
							{
								//el password es incorrecto
								$this->errors[]="La contraseña es incorrecta";
								return 0;
							}
						}
						else
						{
							$this->errors[]="Su cuenta no puede ser habilitada";
							return 0;
						}
					}
				}
				else
				{
					//si el cliente no existe
					$this->errors[]="El usuario no existe o no esta correctamente escrito";
					return 0;
				}
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
		// eliminamos la sesion del cliente
		$_SESSION = array();
		@session_destroy();
		Util::redirect(APP_HOST_URL . '/');
   }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status and the same time
     * verify the permisos from the user. XD
     */
    public static function isUserLoggedIn()
    {#Util::debug($permisos,1,1,'variables de permisos');
        if (isset($_SESSION['cliente']['nombre']))
		{
				return true;
        }
        // default return
        return false;
    }
}
