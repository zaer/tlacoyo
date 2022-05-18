<?php
	$dir=$_SERVER["SERVER_ADDR"];
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		define("WIN2",1);
	else
		define("WIN2",0);

	define("APP_PROFILE",0);
	if (APP_PROFILE == 0)
	{
		if(WIN2==1)
		{
			define("APP_HOST_URL", "http://" . $_SERVER["HTTP_HOST"]);
			define("APP_ROOT_PATH", $_SERVER['DOCUMENT_ROOT']); //todo por el maldito y miserable windows... o XAMPP
		}
		else
		{
			if($dir=='127.0.0.1')
			{
				define("PRODUCTIVO",false);
				define("APP_HOST_URL", "http://" . $_SERVER["HTTP_HOST"]);
				define("APP_DB_HOST", "localhost");
				define("APP_DB_PORT",3306);
				define("APP_DB_USER", "zaer");
				define("APP_DB_PASSWORD", "");
				define("APP_DB_CATALOG", "blue");// temporal
			}
			else
			{
				define("PRODUCTIVO",true);
				define("APP_HOST_URL", "https://" . $_SERVER["HTTP_HOST"]);
				define("APP_DB_HOST", "localhost");
				define("APP_DB_PORT",3306);
				define("APP_DB_USER", "zaer");
				define("APP_DB_PASSWORD", "");
				define("APP_DB_CATALOG", "blue");// temporal
			}
			define("APP_ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);
		}
	}
	else
	{
		define("APP_HOST_URL", "https://" . $_SERVER["HTTP_HOST"]);
		define("APP_ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);
		define("APP_DB_PORT",9955);

		define("APP_DB_HOST", "");
		define("APP_DB_USER", "");
		define("APP_DB_PASSWORD", "");
		define("APP_DB_CATALOG", "");// temporal
	}

	/*
	$sys = new System();
	$sys->conexion->where("nom","titulo");
	$app_name = $sys->conexion->getOne("info_site");

	define("APP_NAME",$app_name["val"]);
	define("APP_TITLE",$app_name["val"]);
	*/
	define("APP_VER","1.0");
	define("APP_DEFAULT_CONTROLLER", "InicioController");	//El controlador por defecto a cargar
	define("APP_ASSETS", APP_HOST_URL . "/assets");			//Los activos CSS,IMG,JS
	define("APP_IMG_URL", APP_ASSETS . "/images");				//Los activos CSS,IMG,JS
	define("APP_CSS_URL", APP_ASSETS . "/css");				//Los activos CSS,IMG,JS
	define("APP_JS_URL", APP_ASSETS . "/js");				//Los activos CSS,IMG,JS
	define("APP_BIN_PATH", APP_ROOT_PATH . "/app");			//Core, Utils, etc... SE QUITA "/" por razones extrañas :S
	define("APP_CLASS_PATH", APP_BIN_PATH . "/classes/");
	define("APP_MODELS_PATH", APP_BIN_PATH . "/Models");
	define("APP_VIEW_PATH", APP_BIN_PATH . "/views");
	define("APP_CONTROLLER_PATH", APP_BIN_PATH . "/controllers");
	define("APP_WIDGET_PATH", APP_BIN_PATH . "/views/widgets");
	define("APP_MAILS_PATH", APP_BIN_PATH . "/mails");
	define("APP_IMG_PATH", APP_ROOT_PATH . "/assets/images");
	define("APP_DOCS_PATH",APP_ROOT_PATH."DOCS");
	define("APP_DOCS_URL",APP_HOST_URL."/DOCS");

	define("TIME_INTERVAL",5);
	define("LAN","es");
	define("UNITS","metric");

	if(PRODUCTIVO)
	{
		define("APP_DEBUG", false);
		define("CONEKTA_PUBLIC_KEY","");
		define("CONEKTA_PRIVATE_KEY","");
		define("API_KEY_MAPS","");
		define("ESTADO","productivo");
	}
	else
	{
		define("APP_DEBUG", true);
		define("CONEKTA_PUBLIC_KEY","");
		define("CONEKTA_PRIVATE_KEY","");
		#define("CONEKTA_PUBLIC_KEY","");
		#define("CONEKTA_PRIVATE_KEY","");
		define("API_KEY_MAPS",'');
		define("ESTADO","Desarrollo");
	}
/*
	$sys->conexion->where("nom","twitter");
	$twit=$sys->conexion->getOne("info_site");
	$sys->conexion->where("nom","fbid");
	$fbid = $sys->conexion->getOne("info_site");

	define("LNK_FB",$fbid["fbid"]);
	define("LNK_TW",$twit["val"]);
*/
	define("TMS_DOC",APP_ROOT_PATH."/DOCS");
	require_once(APP_CLASS_PATH . "/core/AppException.php");

	class App extends System
	{
		private static function load_file($path, $data = NULL, $once = true)
		{
			if (file_exists($path))
			{
				if ($once)
				{
					require_once($path);
				}
				else
				{
					require($path);
				}
				return true;
			}
			else
			{
				return false;
			}
		}

		public static function load_class($class)
		{
			if (!App::load_file(APP_CLASS_PATH . "/" . $class . ".php"))
			{
				throw new AppException("Error: clase '".APP_CLASS_PATH.$class."' no encontrada.");
			}
		}

		public static function load_models($class)
		{
			if (!App::load_file(APP_MODELS_PATH . "/" . $class . ".php"))
			{
				throw new AppException("Error: Modelo '" . $class . "' no encontrado.");
			}
		}

		public static function load_view($view, $data)
		{
			if (!App::load_file(APP_VIEW_PATH . "/" . $view . ".php", $data, false))
			{
				#throw new AppException("Error: página '" . $view . "' no encontrada.");
				Util::redirect(APP_HOST_URL."/error/404");
			}
		}

		public static function load_controller($controller)
		{
			if (!App::load_file(APP_CONTROLLER_PATH . "/" . $controller . ".php"))
			{
				throw new AppException("Error: controlador '" . $controller . "' no encontrado.");
			}
		}

		public static function load_widget($widget,$data)
		{
			if(!App::load_file(APP_WIDGET_PATH."/".$widget.".php",$data,false))
			{
				//throw new AppException("El widget ({$widget}) al que intenta acceder no esta disponible.");
				Util::debug($widget,"WIDGET");
			}
		}

		public static function get_img_url($img)
		{
			return APP_IMG_URL . "/" . utf8_encode($img);
		}

		public static function get_assets($data)
		{
			return APP_ASSETS."/".utf8_encode($data);
		}

		public static function get_css_url($css)
		{
			return APP_CSS_URL . "/" . $css;
		}

		public static function get_js_url($js)
		{
			return APP_JS_URL . "/" . $js;
		}

		public static function putLib($file,$tipo)
		{
			switch ($tipo)
			{
				case 'js':
					$val = "<script type='text/javascript' src='".APP_ASSETS."/".$file."'></script>\n";
					echo $val;
					break;
				case 'css':
					$val = "<link rel='stylesheet' href='".APP_ASSETS."/".$file."' type='text/css' media='all'/>\n";
					echo $val;
					break;
				default:
					# code...
					break;
			}
		}

		public static function putCss($file)
		{
			$val = "<link rel='stylesheet' href='".App::get_css_url($file)."' type='text/css' media='all'/>\n";
			return $val;
		}

		public static function putJs($file)
		{
			$val = "<script type='text/javascript' src='".App::get_js_url($file)."'></script>\n";
			return $val;
		}

		public static function putImg($file,$class="",$style="",$alt="")
		{
			$val = "<img src='".App::get_img_url($file)."' alt='".$alt."' class='".$class."' style='".$style."'>\n";
			return $val;
		}
	}

	function super_charger($class_name)
    {
        if(file_exists(APP_MODELS_PATH."/".$class_name.".php"))
        {
            App::load_models($class_name); // clases por defecto para cargar en todo proyecto
        }
        else
        {
            App::load_class($class_name); //clases personalizadas para cada proyecto
        }
    }
    spl_autoload_register('super_charger');

	App::load_class("core/Controller");
	App::load_class("core/DataBase");
	App::load_class("core/twitter/codebird"); //cargamos  clase para twitter
	#App::load_class("core/DataBase2");
	App::load_class("core/FileUtil");
	App::load_class("core/Util");
	App::load_class("core/GUIUtil");
	App::load_class("core/FormUtil");
	#App::load_class("core/JavaScriptPacker");
	App::load_class("core/DateUtil");
	App::load_class("core/Mailer");
	App::load_class("core/password");
	#App::load_class("libs/BrowserDetection");
	App::load_class("core/Cache");
	App::load_class("libs/Smarty/Smart");

	function exception_handler($exception)
	{
		GUIUtil::error_msg("error", "Error",$exception->getMessage());
	}
	set_exception_handler('exception_handler');
?>
