<?php
#=======================================#
#	coded by: Moises Espindola			#
#	nick: zaer00t						#
#	e-mail: zaer00t@gmail.com 			#
#	www: http://creasati.com.mx			#
#	date: 27/Noviembre/2018				#
#=======================================#

class Util
{
	public static function gen_rand_str() {
		return uniqid() . uniqid();
	}

	public static function toupper($str) {
		return strtr(strtoupper($str),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
	}

	public static function tolower($str) {
		return strtr(strtolower($str),"ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ", "àèìòùáéíóúçñäëïöü");
	}

	public static function url($controller, $function, $params) {
		$url = APP_HOST_URL;

		if ($controller != "") {
			$url .= "/" . $controller;
		}

		if ($function != "") {
			$url .= "/" . $function;
		}

		if ($params != NULL && count($params) > 0) foreach($params as $param) {
			$url .= "/" . $param;
		}

		return $url;
	}

	public static function alert($msg) {
		?>
		<script type="text/javascript">
			alert("<?=$msg?>");
		</script>
		<?php
	}

	public static function alert_and_redirect($msg, $url) {
		?>
		<script type="text/javascript">
			alert("<?=$msg?>");
			window.location.href = '<?=$url?>';
		</script>
		<?php
	}

	public static function alert_and_redirectparent($msg, $url) {
		?>
		<script type="text/javascript">
			alert("<?=$msg?>");
			parent.location.href = '<?=$url?>';
		</script>
		<?php
	}

	public static function redirect($url) {
		header('Location: ' . $url) ;
		exit();
	}

	public static function js_redirect($url) {
		?>
		<script type="text/javascript">
			window.location.href = '<?=$url?>';
		</script>
		<?php
	}

	public static function strtolower($str) {
		$str = mb_strtolower($str, 'UTF-8');
		$chars = array("/","\\","=","!","¡","%","'",'"',"&","+","$","#","*","(",")","{","}","<",">","~","^","[","]","?",".",":","¿","´","`","|","°","@");
		$str = str_replace(" ", "_", $str);

		foreach($chars as $char) {
			$str = str_replace($char, "", $str);
		}

		return $str;
	}

	/*
	 *	funcion para mostrar datos de debug esta funcion cuenta con 3 parametros, 2
	 *	obligatorios y uno opcional, se entiende asi que no pasare a mas explicacion.
	 */
	public static function debug($cadena,$desc='',$status=1,$die=0)
	{
		//si "$status == 0" no mostramos nada
		if($status!=1)
		{
			return 0;
		}
		else
		{
			echo "<table class='table'><thead><th style='border:1px solid #eee; width:100%; text-align:left; background-color:crimson; color:#fff; font-family:Courier,sans-serif;'>".$desc."</th></thead>";
			echo "<tbody><tr><td><pre>";
			print_r($cadena);
			echo "</pre></td></tr></tbody></table>";
		}
		if($die==1)die();
	}

	/* funciones para validar algunas cosas entre ellas correo, usuario etc... */
	/*
	 * 	funcion que nos permite validar direccion de
	 * 	email y retorna TRUE or FALSE Segun sea le caso
	*/
	static function validaCorreo($email)
	{
		$patron="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
		$a=preg_match($patron,$email);
		if($a==1)
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * 	funcion que nos permite validar direccion de
	 * 	url
	*/
	static function validaUrl($url)
	{
		$patron="/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \?=.-]*)*\/?$/";
		if(preg_match($patron,$url))
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * 	funcion que nos permite validar el password con un
	 *
	*/
	static function validaPassword($pass)
	{
		$patron="/^(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/";
		if(preg_match($patron,$pass)==TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	/*
	 * 	funcion que nos permite validar el usuario
	 * 	y verifica si existe
	 *
	*/
	static function validaUsuario($usuario)
	{
		if (preg_match('/^[a-z\d_]{4,28}$/i', $usuario))
			return TRUE;
		else
			return FALSE;
	}
	/*
	 * 	funcion que nos permite validar el Num. de tel
	 *
	*/
	static function validaNoTel($numero)
	{
		if (preg_match('/^(\(?[0-9]{3,3}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/', $numero))
			return TRUE;
		else
			return FALSE;
	}

	/*
		construimos un calendario
	*/
	static function calendario($dia=1,$mes=1,$anio=1)
	{
		/*
			mktime
			hora, minuto, segundo, mes, dia, año
		*/


		if($dia==1) $dia=date("j",mktime());	//obtenemos el numero del dia actual
		if($mes==1) $mes=date("n",mktime());	//obtenemos el numero del mes actual
		if($anio==1) $anio=date("Y",mktime());	//obtenemos el numero del anio actual
	/*
		$dia_semana=date("N",mktime());
		$dia_anio=date("z",mktime());
		$num_semana = date("W",mktime());
		$num_mes=date("n",mktime());
	*/
		$num_dias_mes=date("t",$mes);	//obtnemos el numero de dias que tiene el mes
		$meses=array(
				1=>"ENERO",2=>"FEBRERO",3=>"MARZO",4=>"ABRIL",
				5=>"MAYO",6=>"JUNIO",7=>"JULIO",8=>"AGOSTO",
				9=>"SEPTIEMBRE",10=>"OCTUBRE",11=>"NOVIEMBRE",12=>"DICIEMBRE"
				);
		$dias=array(1=>"Lunes",2=>"Martes",3=>"Miercoles",4=>"Jueves",5=>"Viernes",6=>"Sabado",7=>"Domingo");
		#construimos la cabecera del calendario
		echo "<table border='1' style='border-collapse:collapse; border:1px dashed #369;font-family:Arial,Tahoma,sans-serif;'><tr>";
		echo "<td>1</td><td colspan='5' align='center'><h2>".$meses[$mes]."&nbsp;".$anio."</h2></td><td>6</td></tr><tr>";
		for($i=0;$i<7;$i++)
		{	#construimos los dias de la semana (l,m,..,s,d)
				echo "<td style='border:1px dotted #369;padding:10px;'>".$dias[$i+1]."</td>";
		}
		echo "</tr>"; #finalizamos la columna de los nombres de los dias de la semana
		//obtenemos el dia de la semana del dia primero
		$dia_primero=date("N",mktime(0,0,0,$mes,1,$anio));
		// generamos la tabla de acuerdo a la cantidad de dias de este mes
		$semanas_mes=(($dia_primero-1)+$num_dias_mes); //obtenemos cauntas semanas tiene este mes
		$dia_inicial=1;$d=0;$days=1;
		echo "<tr>";
		while($semanas_mes)
		{
			if($d==7)//corta los dias para generar una nueva fila
			{
				echo "</tr><tr>";
				$d=0;
			}
			if($days>=($dia_primero))
			{
				if($dia_inicial==$dia){
					echo "<td width='30' height='40' style='background-color: #efefef; border:1px dotted #369;padding:10px;font-size:10px;font-weight:bold;color:red;' valign='bottom'>".$dia_inicial."</td>";}
				else
				{
					echo "<td width='20' height='40' style='border:1px dotted #369;padding:10px;font-size:10px;' valign='bottom'>".$dia_inicial."</td>";
				}
				$dia_inicial++;
			}
			else
			{
				echo "<td style='border:1px dotted #369;padding:10px;'>&nbsp;</td>";
			}
			$d++;
			$days++;
			$semanas_mes--;
		}
		echo "</tr></table>";
	}

	/*
	 * funcion para monitorear si un host esta abajo, esto con peticiones al puerto 80
	 * de igual manera se pueden realizar derivaciones de esta funciona para distitnos
	 * puertos.
	*/
	function isAlive($host, $find)
	{
		$fp = fsockopen($host, 80, $errno, $errstr, 10);
		if(!$fp)
		{
			echo "$errstr ($errno)\n";
		}
		else
		{
			$header = "GET / HTTP/1.1\r\n";
			$header .= "Host: $host\r\n";
			$header .= "Connection: close\r\n\r\n";
			fputs($fp, $header);
			while(!feof($fp))
			{
				$str .= fgets($fp, 1024);
			}
			fclose($fp);
			return (strpos($str, $find) !== false);
		}
	}

	/*
		funcion para comprobar si el usuario logeado es SuperAdmin
	*/
	public static function ISA()
	{
		if($_SESSION["usuario"]["perfil"]!='GOD') Util::redirec(APP_HOST_URL);
	}

	public static function mem()
	{
		$mem_usage = memory_get_usage(true);
		if ($mem_usage < 1024)
				echo "<span style='color:#aaa;font-size:10px;'>".$mem_usage." bytes</span>";
		elseif ($mem_usage < 1048576)
				echo "<span style='color:#aaa;font-size:10px;'>".round($mem_usage/1024,2)." kb</span>";
		else
				echo "<span style='color:#aaa;font-size:10px;'>".round($mem_usage/1048576,2)." mb</span>";
		echo "<br/>";
	}

	public static function ourl($url,$nom='',$target="",$cl="")
	{
		$url = str_replace(" ","_",$url);
		echo "<a href='".APP_HOST_URL."/".$url."' target='".$target."' class='".$cl."'>".$nom."</a>";
	}

	public static function time_elapsed_string($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		$string = array(
			'y' => 'año',
			'm' => 'mes',
			'w' => 'semana',
			'd' => 'día',
			'h' => 'hora',
			'i' => 'minuto',
			's' => 'segundo',
		);

		foreach ($string as $k => &$v)
		{
			if ($diff->$k)
			{
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			}
			else
			{
				unset($string[$k]);
			}
		}
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ?  'Hace '.implode(', ', $string) : 'en este momento';
	}
	
	/*
	 *	Muestra un mensaje de información al usuario
	 */
	public static function msg($titulo,$msg,$sugerencia,$c=0)
	{
		?>
		
			<div class="col-md-offset-2col-md-6">
				<div class="panel">
					<div class="panel-body" style="box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.6);">
						<div class="pad-ver mar-top text-main">
							<i class="demo-pli-consulting icon-4x"></i>
						</div>
						<p class="text-lg text-semibold mar-no text-main" style="background-color:#2A4B7C;color:#fff;font-weight:bold;margin:5px;padding:10px;"><?=$titulo?></p>
						<p class="text-muted" style="margin:10px"><?=$msg?></p>
						<p class="text-sm" style="margin:10px"><?=$sugerencia?></p>
						<?php
						if($c==1)
							echo "<a class='btn btn-primary mar-ver' href='mailto:mes@bluedeb.com'>Contactar</a>";
						?>
					</div>
				</div>
			</div>
		<?
	}

	public static function isUp($ip,$port)
	{
		$socket = fsockopen($ip,$port);
		if(!$socket)
		{
			return false;
		}
		else
		{
			socket_close($socket);
			return true;
		}
		stream_set_blocking($socket, 0);
		stream_set_blocking(STDIN, 0);

		do{
			echo "$ ";
			$read = array( $socket, STDIN);
			$write = NULL;
			$except = NULL;

			if(!is_resource($socket)) return;
			$num_changed_streams = @stream_select($read, $write, $except, null);
			if(feof($socket)) return ;
			if($num_changed_streams  === 0) continue;
			if (false === $num_changed_streams)
			{
				/* Error handling */
				var_dump($read);
				echo "Continue\n";
				die;
			}
			elseif($num_changed_streams > 0)
			{
				echo "\r";
				$data = fread($socket, 4096);
				if($data !== "")
					echo "<<< $data";

				$data2 = fread(STDIN, 4096);

				if($data2 !== "")
				{
					echo ">>> $data2";
					fwrite($socket, trim($data2));
				}
			}
		}while(true);
	}

	/*
	 * Metodo para identificar el tipo de dispositivo que entra al sitio
	 */
	public static function tipoConexion()
	{
		$tablet_browser = 0;
		$mobile_browser = 0;
		$body_class = 'desktop';

		if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
		{
			$tablet_browser++;
			$body_class = "tablet";
		}

		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
		{
			$mobile_browser++;
			$body_class = "mobile";
		}

		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
		{
			$mobile_browser++;
			$body_class = "mobile";
		}

		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-',
			'siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr',
			'webc','winw','winw','xda ','xda-'
		);

		if (in_array($mobile_ua,$mobile_agents))
		{
			$mobile_browser++;
		}

		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0)
		{
			$mobile_browser++;
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));

			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua))
			{
				$tablet_browser++;
			}
		}

		if ($tablet_browser > 0) // Si es tablet has lo que necesites
			return 1;
		else if ($mobile_browser > 0) // Si es dispositivo mobil has lo que necesites
			return 2;
		else // Si es ordenador de escritorio has lo que necesites
			return 3;

		return 0;
	}
	
	public static function limpiaCadena($cadena)
		{
			$cadena = str_replace( 'À', 'A', $cadena );
			$cadena = str_replace( 'Á', 'A', $cadena );
			$cadena = str_replace( 'È', 'E', $cadena );
			$cadena = str_replace( 'É', 'E;',$cadena );
			$cadena = str_replace( 'Ì', 'I', $cadena );
			$cadena = str_replace( 'Í', 'I', $cadena );
			$cadena = str_replace( 'Î', 'I', $cadena );
			$cadena = str_replace( 'Ï', 'I', $cadena );
			$cadena = str_replace( 'Ò', 'O', $cadena );
			$cadena = str_replace( 'Ó', 'O', $cadena );
			$cadena = str_replace( 'Ù', 'U', $cadena );
			$cadena = str_replace( 'Ú', 'U', $cadena );
			$cadena = str_replace( 'à', 'a', $cadena );
			$cadena = str_replace( 'á', 'a', $cadena );
			$cadena = str_replace( 'è', 'e', $cadena );
			$cadena = str_replace( 'ñ', 'n', $cadena );
			$cadena = str_replace( 'é', 'e', $cadena );
			$cadena = str_replace( 'ì', 'i', $cadena );
			$cadena = str_replace( 'í', 'i', $cadena );
			$cadena = str_replace( 'ò', 'o', $cadena );
			$cadena = str_replace( 'ó', 'o', $cadena );
			$cadena = str_replace( 'ù', 'u', $cadena );
			$cadena = str_replace( 'ú', 'u', $cadena );
			$cadena = str_replace( '°', '', $cadena );
			$cadena = str_replace( '%', '', $cadena );
			$cadena = str_replace( '°', '',$cadena);
			$cadena = str_replace( '!', '',$cadena);
			$cadena = str_replace( '"', '',$cadena);
			$cadena = str_replace( '#', '',$cadena);
			$cadena = str_replace( '$', '',$cadena);
			$cadena = str_replace( '%', '',$cadena);
			$cadena = str_replace( '&', '',$cadena);
			$cadena = str_replace( '/', '',$cadena);
			$cadena = str_replace( '(', '',$cadena);
			$cadena = str_replace( ')', '',$cadena);
			$cadena = str_replace( '=', '',$cadena);
			$cadena = str_replace( '?', '',$cadena);
			$cadena = str_replace( '¡', '',$cadena);
			$cadena = str_replace( '¿', '',$cadena);
			$cadena = str_replace( '\'', '',$cadena);
			$cadena = str_replace( '|', '',$cadena);
			$cadena = str_replace( '\\', '',$cadena);
			$cadena = str_replace( '^', '',$cadena);
			$cadena = str_replace( '~', '',$cadena);
			$cadena = str_replace( '@', '',$cadena);
			return $cadena;
		}

		public static function set_utf8($string)
		{
			return utf8_encode($string);
		}

		//metodo para remplazar el width o height de fuentes como imagenes
		//o iframes entre otras
		public static function replaceWH($fuente,$w='100%',$h='')
		{
			$fuente;
			$w='width="'.$w.'"';
			$h='height="'.$h.'"';
			preg_match_all('/(width=("|\')[0-9]{3,}("|\'))/i',$fuente,$width);
			preg_match_all('/(height=("|\')[0-9]{3,}("|\'))/i',$fuente,$height);

			$salida=str_replace(array_shift($width[0]),$w,$fuente);
			$salida=str_replace(array_shift($height[0]),$h,$salida);

			return $salida;
		}

		public static function cortaTexto($Txt, $largo='200')
		{
			$Txt = substr($Txt, 0, $largo);
			#$posicion = strrpos($Txt, " ");
			#$Txt = substr($Txt, 0, $posicion);
			return $Txt;
		}

		/*
			METODO EXPERIMENTAL PARA CONSTRUIR LOS SETTERS Y GETTERS
			COMO PARAMETROS:
			$db CONEXION A BASE DE DATOS
			$tabla TABLA A LA CUAL SE REALIZARA EL BARRIDO DE VARS
			$salida (OPCIONAL) DETERMINA SI LA SALIDA ES POR ARCHIVO
		*/
		public static function buildSetGet($db,$tabla)
		{
			#$r1=$db->select($tabla,"*","id>0",array(null));
			$r1 = $db->select("usuarios","*","id>0",array(0));
			if(empty($r1) || $r1==null)
			{
				throw  new AppException("error con la operacion");
			}
			else
			{
				echo "vamos equipo vamos muy bien";
			}

		}

		/*
		 *	Coloca una marca de agua sobre una imagen a partir de una imagen png
		 *	con transparencia.
		 */
		public static function setMarca($imagen,$opacidad,$x,$y)
		{
			App::load_class("agua/ImageWorkshop");
			$norwayLayer = new ImageWorkshop(array("imageFromPath" => $imagen));
			$watermarkLayer = new ImageWorkshop(array(
				"imageFromPath" => APP_IMG_PATH."/logo.png",
			));

			$watermarkLayer->opacity($opacidad);
			$norwayLayer->addLayer(1,
			$watermarkLayer,$y, $y, "LB");
			$image = $norwayLayer->getResult();
			#header('Content-type: image/jpeg');
			imagejpeg($image, $imagen, 95);
			#die();
		}

		/*
		 * Guarda una imagen proveniente de un formulario, a su vez permite colocarle nombre,
		 * directorio y redimencionar su tamaño:
		 * args = $file_id = ID del formulario $titulo = Nombre a colocar a la imagen,
		 * $directorio = lugar donde se va a guardar, $w= ancho , $h = alto
		 */

		public static function guarda_imagen($file_id,$titulo,$directorio,$w='',$h='',$zip='75')
		{
			//checamos si se paso la imagen por post multipart
			if(FileUtil::is_uploaded($file_id))
			{
				$cadena = (str_replace(' ', '', $titulo));
				$cadena = Utilidades::limpiaCadena($cadena);
				$nombre_archivo = $cadena . "_" . uniqid() . '.jpg';

				$tmp_name = Util::gen_rand_str();

				if(!is_dir(APP_IMG_PATH."/".$directorio))
				{
					# si el directorio no existe
					# creamos el directorio
					#echo APP_IMG_PATH."/".$directorio;
					if(!mkdir(APP_IMG_PATH."/".$directorio,0777,true))
					{
						throw new AppException("No se puede crear el directorio <b>".str_replace("//","/",APP_IMG_PATH)."/".$directorio."</b>");
					}
				}

				FileUtil::save_file($file_id, APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name, 20000);
				Utilidades::setMarca(APP_IMG_PATH."/".$directorio."/".$tmp_name,3,0,0);
				$image = new ImageEdit();
				$thumb = new ImageEdit();

				$image->load(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);

				$thumb->load(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);
				#$thumb->resize(400,300);
				$thumb->resizeToWidth(250);
				$thumb->save(APP_IMG_PATH .'/'.$directorio.'/'.str_ireplace(".jpg","_thumb.jpg",$nombre_archivo),IMAGETYPE_JPEG, $zip);

				if($w!='' and $h!='')$image->resize($w,$h);
				if($w!='')$image->resizeToWidth($w);
				if($h!='')$image->resizeToHeight($h);

				$image->save(APP_IMG_PATH . '/'.$directorio.'/' . $nombre_archivo,IMAGETYPE_JPEG, $zip);
				unlink(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);
				return $nombre_archivo;
			}
			else
			{
				return "";
			}
		}

		/*
		 * Guarda varias imagene provenientes de un formulario, a su vez permite colocarle nombre,
		 * directorio y redimencionar su tamaño:
		 * args = $file_id = ID del formulario $titulo = Nombre a colocar a la imagen,
		 * $directorio = lugar donde se va a guardar, $w= ancho , $h = alto
		 */
		public static function guardar_multi_imagen($file_id,$titulo,$directorio,$w='',$h='')
		{
			$size=20000;
			$archivos = count($_FILES[$file_id]['name']);
			if($archivos > 1)
			{
				$fotos = array();
				for($i=0; $i<$archivos; $i++)
				{
					if ($_FILES[$file_id]["size"][$i] < ($size * 1024))
					{
						if ($_FILES[$file_id]["error"][$i] > 0)
						{
							throw new AppException("Error al subir multiples archivos" . $_FILES[$file_id]["name"][$i] . ": " . $_FILES[$file_id]["error"][$i]);
						}
						else
						{
							$cadena=str_replace(' ', '', $titulo);
							$cadena = Utilidades::limpiaCadena($cadena);
							#$nombre_archivo = $cadena . "_" . uniqid() . '.jpg';
							$nombre_archivo = $cadena . "_" . Utilidades::randomText(4) . '.jpg';
							$tmp_name = Util::gen_rand_str();
							if(!is_dir(APP_IMG_PATH."/".$directorio))
							{
								# si el directorio no existe
								# creamos el directorio
								if(!mkdir(APP_IMG_PATH."/".$directorio,0777,true))
								{
									throw new AppException("Ocurrio un erro al crear el directorio {$directorio}");
								}
							}

							if(!move_uploaded_file($_FILES[$file_id]["tmp_name"][$i], APP_IMG_PATH."/{$directorio}/".$tmp_name))
							{
								throw new AppException("Error al subir archivo >>" . $_FILES[$file_id]["name"][$i]);
							}
							$image = new ImageEdit();
							$thumb = new ImageEdit();//creamos la thumbnail
							$image->load(APP_IMG_PATH . "/{$directorio}/" . $tmp_name);

							$thumb->load(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);
							#$thumb->resize(400,300);
							$thumb->resizeToWidth(200);
							$thumb->save(APP_IMG_PATH .'/'.$directorio.'/'.str_ireplace(".jpg","_thumb.jpg",$nombre_archivo),IMAGETYPE_JPEG, 100);


							if($w!='' and $h!='')$image->resize($w,$h);
							if($w!='')$image->resizeToWidth($w);
							if($h!='')$image->resizeToHeight($h);

							$image->save(APP_IMG_PATH . "/{$directorio}/" . $nombre_archivo,IMAGETYPE_JPEG, 100);
							Utilidades::setMarca(APP_IMG_PATH."/".$directorio."/".$nombre_archivo,3,0,0);
							unlink(APP_IMG_PATH . "/{$directorio}/" . $tmp_name);
							$fotos[]= $nombre_archivo;
						}
					}
					else
					{
						return APP_FILEUPLOAD_ERROR_SIZE;
					}
				}
				return implode("|",$fotos);
			}
		}

		/* funcion que genera un texto aleatorio de n caracteres */
		public static function randomText($len=10)
		{
			$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$caracteresLength = strlen($caracteres);
			$randomString = '';
			for ($i = 0; $i < $len; $i++)
			{
				$randomString .= $caracteres[rand(0, $caracteresLength - 1)];
			}
			return $randomString;
		}

		// Function to get the client ip address
		public static function get_client_ip_server()
		{
			$ipaddress = '';
			if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

		public static function genPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
		{
			$sets = array();
			if(strpos($available_sets, 'l') !== false)
				$sets[] = 'abcdefghjkmnpqrstuvwxyz';
			if(strpos($available_sets, 'u') !== false)
				$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
			if(strpos($available_sets, 'd') !== false)
				$sets[] = '23456789';
			if(strpos($available_sets, 's') !== false)
				$sets[] = '!@#$%&*?';
			$all = '';
			$password = '';
			foreach($sets as $set)
			{
				$password .= $set[array_rand(str_split($set))];
				$all .= $set;
			}
			$all = str_split($all);
			for($i = 0; $i < $length - count($sets); $i++)
				$password .= $all[array_rand($all)];
			$password = str_shuffle($password);
			if(!$add_dashes)
				return $password;
			$dash_len = floor(sqrt($length));
			$dash_str = '';
			while(strlen($password) > $dash_len)
			{
				$dash_str .= substr($password, 0, $dash_len) . '-';
				$password = substr($password, $dash_len);
			}
			$dash_str .= $password;
			return $dash_str;
		}
		/*
			distancia entre 2 puntos en un mapa
			Cada uno de los puntos debe corresponder a su latitud y longitud
		*/
		public static function haversine_distance($lat1, $lon1, $lat2, $lon2)
		{
			if($lat1=='' || $lon1=='' || $lat2=='' || $lon2=='') return 0;

			$lat1 = deg2rad($lat1);
			$lon1 = deg2rad($lon1);
			$lat2 = deg2rad($lat2);
			$lon2 = deg2rad($lon2);
			$latD = $lat2 - $lat1;
			$lonD = $lon2 - $lon1;
			$angle = 2 * asin(sqrt(pow(sin($latD / 2) , 2) + cos($lat1) * cos($lat2) * pow(sin($lonD / 2) , 2)));
			return $angle * 6371000;
		}
		/*
			obtiene la distancia recorrida de un vehiculo de una fecha inicial hasta el presente dia
		*/
		public static function kms($f1,$f2,$trip)
		{
			$fecha_ini = $f1;
			$fecha_fin = $f2;
			
			$db = new DataBase();
			$db->connect();

			$usr_id=$_SESSION["usuario"]["id_usuario"];
			$rec_id=10;
			$perm = new Permisos($db,$usr_id,$rec_id);
			$perm->getPermiso();

			if($perm->lectura())
			{
				$posiciones = new Positions($db);
				$datos = $posiciones->repetirRuta($usr_id,$trip,$fecha_ini,$fecha_fin);
				#Util::debug($datos,"datos",1,1);
				/* parseamos las coordenadas */
				$r1=0;
				$distancia = 0;
				$trips = array();

				foreach ($datos as $ruta)
				{
					$id=$latitud=$longitud="";
					$r1++;

					$trips[$r1]["Latitud"]=$latitud=$ruta->getLatitude();
					$trips[$r1]["Longitud"]=$longitud=$ruta->getLongitude();
							
					if($r1>1)
					{
						$d1 = Utilidades::haversine_distance(
							$trips[$r1-1]["Latitud"],$trips[$r1-1]["Longitud"],
							$trips[$r1]["Latitud"],$trips[$r1]["Longitud"]);
						$distancia=$distancia+$d1;
					}
				}
				/* fin parseo */
			}
			else
			{
				echo "ERROR: EL USUARIO NO TIENE PERMISOS";
			}
			return $distancia;
		}
		
		public static function chisme($json_in)
		{
			$f1 = fopen("/home/zaer/logBlue.log","a+");
			$intro="\tLog Bluedeb: [ ".date("Y-m-d H:i:s")." ]\n[ ============================================================================================== ]\n";
			fwrite($f1,$intro,strlen($intro));
			fwrite($f1,json_encode($json_in)."\n",strlen(json_encode($json_in)."\n"));
			$intro="[ ============================================================================================== ]\n\n";
			fwrite($f1,$intro,strlen($intro));
			fclose($f1);
		}
		
		public static function log(&$file,&$metodo,&$linea,$data)
		{
			$db = new DataBase();
			$db->connect();

			$msg="[ ".$file."::".$linea." ]<--->[ ".date("Y-m-d # H:i:s")." ]\n";
			$msg.=$data; //"Info: ".current($r1["error"])."\n\n";
			$f1=fopen("/home/zaer/docs_oxxo.txt","a+");
			fwrite($f1,$msg,strlen($msg));
			fclose($f1);
			Error_log::reg($db,$file,$metodo,$linea,$msg);
		}
		
		public static function getIp()
		{
			$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
				else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
						else if(isset($_SERVER['HTTP_X_FORWARDED']))
							$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
								else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
									$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
										else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
											$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
												else if(isset($_SERVER['HTTP_FORWARDED']))
													$ipaddress = $_SERVER['HTTP_FORWARDED'];
														else if(isset($_SERVER['REMOTE_ADDR']))
															$ipaddress = $_SERVER['REMOTE_ADDR'];
																else
															$ipaddress = 'UNKNOWN';
															return $ipaddress;
		}
}
?>
