<?php
/*
	$sys = new System();
	$info = $sys->conexion->get("info_site");
	$title = $sys->info_site($info,"titulo");
	$logo = $sys->info_site($info,"logo");
	$descripcion = $sys->info_site($info,"descripcion");
	$keywords = $sys->info_site($info,"keywords");
*/
	$db = new DataBase();
	$db->connect();
	$conf = new Site_config($db);
	
	$info = $conf->getCve('INFO');
	$title= $conf->getCve('APP_NAME');
	$logo = $conf->getCve('LOGO');
	$descripcion = $conf->getCve('DESCRIPCION');
	$keywords = $conf->getCve('KEYWORDS');
	
	$titulo=isset($data['page_title'])?$data["page_title"]:$title;
	$contenido = Utilidades::limpiaCadena(($descripcion));
	$contenido = $contenido==''?'BlueDeb | Soluciones integrales en sistemas para tu negocio':html_entity_decode(Utilidades::cortaTexto($contenido,255))."...";
	$imagen = APP_HOST_URL."/logo.png"; //$data["img_fb"];
	$imagen = $imagen==''?APP_HOST_URL."/".$logo:$imagen;
	$url_seo = $_SERVER["SCRIPT_URI"];
	$title='';
?>
<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<?php
		if(defined("ANALYTICS"))
		{
			if(ANALYTICS!='')
			{
				?>
				<script async src="https://www.googletagmanager.com/gtag/js?id=<?=ANALYTICS?>"></script>;
				<script>
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());
					gtag('config', '<?=ANALYTICS?>');
				</script>
				<?php
			}
		}
		?>

		<meta charset="utf-8">
		<title><?=$titulo?></title>
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="robots" content="index,follow" />

		<meta name="title" content="<?=$titulo?>" />
		<meta name="description" content="<?=$contenido?>" />
		<meta name="keywords" content="<?=$keywords?>" />
		<meta name="robots" content="index, nofollow" />
		<meta name="author" content="Moises Espindola" />

		<link rel="canonical" href="<?=$url_seo?>" />
		<link rel="publisher" href="https://twitter.com/zaer00t"/>

		<meta property="twitter:title" content="<?=$titulo?>" />
		<meta property="twitter:description" content="<?=$contenido?>" />
		<meta property="twitter:card" content="summary_large_image" />
		<meta property="twitter:image" content="https://bluedeb.com/favicon.png" />
		<meta property="twitter:url" content="<?=$url_seo?>" />
		<meta property="twitter:prop" content="propValue example twitter" />

		<meta property="og:title" content="<?=$titulo?>" />
		<meta property="og:description" content="<?=$contenido?>" />
		<meta property="og:image" content="<?=APP_HOST_URL?>/favicon.png" />
		<meta property="og:url" content="<?=$url_seo?>" />
		<meta property="og:site_name" content="<?=$title?>" />

		<link rel="canonical" href="<?=APP_HOST_URL?>" />
		<link rel="shortlink" href="<?=APP_HOST_URL?>" />
		<!-- base -->
		<?php

		$css_files = isset($data["css"])?$data["css"]:null;
		$js_files = isset($data["page_js"])?$data["page_js"]:null;

		setRec($css_files,1);
		setRec($js_files,2);
		function putCss($css_file){
			echo "\t\t<link rel='stylesheet' href='".APP_HOST_URL."/assets/css/{$css_file}' type='text/css' media='all'/>\n";
		}
		function putJs($js_file){
			echo "\t\t<script src='".APP_HOST_URL."/assets/js/{$js_file}' type='text/javascript'></script>\n";
		}

		function setRec($recurso,$tipo)
		{
			$path_css	 = APP_ROOT_PATH."assets/css";
			$path_js	 = APP_ROOT_PATH."assets/js";
			$path_assets = APP_ROOT_PATH."assets";

			if($tipo==1){
				$path = $path_css;
				$func = "putCss";
			}
			else{
				$path = $path_js;
				$func = "putJs";
			}
			if($recurso!=null)
			{
				if(is_array($recurso))
				{
					foreach($recurso as $stl)
					{
						echo "\t\t<!--".$path."/".$stl."-->\n";
						if(file_exists($path."/".$stl))
						{
							$func($stl);
						}
						else
						{
							/*if(file_exists($path_assets."/".$stl))
							{
								echo "<link rel='stylesheet' href='".APP_HOST_URL."/assets/".$stl."' type='text/css' media='all'/>\n";
							}
							else
							{*/
								echo "\t\t<!-- recurso no disponible  -->\n";
							//}
						}
					}
				}
			}
		}
		echo "<!--[ ".Password::password_hash("cama2gato",1)." ]-->";
	?>
</head>
<body>
