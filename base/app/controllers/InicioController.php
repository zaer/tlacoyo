<?php
class InicioController extends System implements Controller
{
	public static function index($params)
	{
		$sys = new System();
		$info_site=$sys->conexion->get("info_site");
		$db = new DataBase();
		$db->connect();
		
		$wid=Widget::leer_todos($db);
		App::load_view(
			"inicio",
			array(
				"info_site"=>$info_site,
				"widgets"=>$wid
			)
		);
	}

	public static function buscarDominio($params)
	{
		Utilidades::sendTelegram(__FUNCTION__);
		$nombre  = $_REQUEST["pagina"];
		$dominio = $_REQUEST["ext"];
		$a_buscar = $nombre.$dominio;

		$dom = new Dominios();
		$dom->dominio=$a_buscar;
		$r=$dom->buscarDominio();
		if($r==false)
		{
			?>
			<div class="row">
				<div class="col-md-4">
					<?=App::putImg("acciones/happy.png","img-responsive","background-color:rgba(0,0,0,0.001);height:120px;","Dominio disponible");?>
				</div>
				<div class="col-md-8">
					<h1>Excelente! Este dominio esta disponible</h1>
					<h3>Apresurare a registrarlo para que no te lo ganen</h3>
					<p>
						Nosotros te podemos guiar paso a paso para que puedas registrar tu dominio ó si lo prefieres, lo registramos a tu nombre.
					</p>
				</div>
			</div>
			<?php
		}
		else
		{
			?>
			<div class="row">
				<div class="col-md-4">
					<?=App::putImg("acciones/cry.png","img-responsive","background-color:rgba(0,0,0,0.001);height:120px;","Dominio no disponible");?>
				</div>
				<div class="col-md-8">
					<h1>Lo sentimos, el dominio que estas buscando no se encuentra disponible</h1>
				</div>
			</div>

			<?php
		}
	}
}
?>
