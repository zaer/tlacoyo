<?php

class PageBuilder
{
	public static function head($data)
	{
		foreach($data as $dato => $key)
		{
			switch ($dato){
				case 'lib': foreach($key as $lib_ls) {$libs[]=$lib_ls;}break;
				case 'css': foreach ($key as $css_ls) {$css[]=$css_ls;}break;
				case 'js': foreach ($key as $js_ls) {$js[]=$js_ls;}break;
				case 'titulo': $title=$key; break; case 'descripcion': $description=$key; break;
				case 'img_fb': $img_fb = $key; break; default: break;
			}
		}
		App::load_view("header",array("page_title"=>$title,"page_description"=>$description,"img_fb"=>$img_fb,"page_js"=>$js,"css"=>$css,"lib"=>$libs));
	}

	public static function header($title="",$description="",$keywords="",$css="",$js="")
	{
		$fb_appid=$ga = "";
		App::load_view("header",array("page_title"=>$title,"page_description"=>$description,"keywords"=>$keywords,"page_js"=>$js,"css"=>$css));
	}

	public static function topbar($seccion,$titulo="",$var="",$bc="") {
		App::load_view(
			"topbar", array(
				"seccion"=>$seccion,
				"titulo"=>$titulo,
				"var"=>$var,
				"breadcrumb"=>$bc,
			)
		);
	}
	public static function breadCrumb($t1,$t2,$link)
	{
		App::load_view("bc",array("t1"=>$t1,"t2"=>$t2,"link"=>$link));
	}
	public static function footer($js=null,$data=null)
	{
		if(!is_null($js) and !is_null($data))
		{
			App::load_view("footer", array("data"=>$data,"js"=>$js));
		}
		else
		{
			if(is_null($data))
			{
				if(is_null($js))
				{
					App::load_view("footer",array(null));
				}
				else
				{
					App::load_view("footer",array("js"=>$js));
				}
			}
			else
			{
				App::load_view("footer",array("data"=>$data));
			}
		}
	}

	public static function foot($data)
	{
		foreach($data as $dato => $key)
		{
			switch ($dato){
				case 'lib':foreach($key as $lib_ls){$libs[]=$lib_ls;}break;
				case 'js':if(is_array($key)){foreach($key as $js_ls){$js[]=$js_ls;}} else {$js=$key;}break;
				default:break;
			}
		}
		App::load_view("footer",array("js"=>$js,"lib"=>$libs));
	}

}
?>
