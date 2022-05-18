<?php
#===================================================#
#	coded by: Moises Espindola						#
#	nick: zaer										#
#	date: 18/01/2021							  	#
#===================================================#

if (1)
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
else
{
	ini_set('display_errors',0);
	error_reporting(0);
}

session_start();
setlocale(LC_CTYPE, 'es');
date_default_timezone_set('America/Mexico_City');
include("app/classes/libs/MySQLi/MysqliDb.php");
include("app/classes/System.php");
require_once("app/app.php");
$la_clase = APP_DEFAULT_CONTROLLER;
$el_metodo = "index";
$los_params = array();
$params = array();
if (isset($_GET["url"]))
{
	$params = explode("/", $_GET["url"]);
}

if(isset($params[0]))
{
	$tmp = ucfirst($params[0])."Controller";
	if(file_exists(APP_CONTROLLER_PATH."/".$tmp.".php" ))
	{
		$la_clase = $tmp;
		App::load_controller($la_clase);
		if(isset($params[1]))
		{
			if(method_exists($la_clase, $params[1]))
			{
				$el_metodo = $params[1];
				for($i = 2; $i < count($params); $i++)
				{
					$los_params[] = $params[$i];
				}
			}
			else
			{
				for ($i = 1; $i < count($params); $i++)
				{
					$los_params[] = $params[$i];
				}
			}
		}
	}
	else
	{
		App::load_controller(APP_DEFAULT_CONTROLLER);
		if (isset($params[0]))
		{
			if (method_exists($la_clase, $params[0]))
			{
				$el_metodo = $params[0];
				for ($i = 1; $i < count($params); $i++)
				{
					$los_params[] = $params[$i];
				}
			}
			else
			{
				for ($i = 0; $i < count($params); $i++)
				{
					$los_params[] = $params[$i];
				}
			}
		}
	}
}
else
{
	App::load_controller(APP_DEFAULT_CONTROLLER);
}

// se realiza el llamado a la case con su metodo y parametros
call_user_func(array($la_clase, $el_metodo) , $los_params);

function memoria()
{
	$mem_usage = memory_get_usage(true);

	if ($mem_usage < 1024)
		echo "<span style='color:crimson;font-size:12px;padding:10px'>Memoria utilizada: ".$mem_usage." bytes</span>";
	elseif ($mem_usage < 1048576)
		echo "<span style='color:crimson;font-size:12px;padding:10px'>Memoria utilizada: ".round($mem_usage/1024,2)." kb</span>";
	else
		echo "<span style='color:crimson;font-size:12px;padding:10px'>Memoria utilizada: ".round($mem_usage/1048576,2)." mb</span>";
	echo "<br/>";
}
#memoria();
#Util::debug($params,"URL");
#Util::debug(memoria(),"Memoria");
?>
