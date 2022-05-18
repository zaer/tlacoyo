<?php
#==================================================#
#     coded by: Moises Espindola                   #
#     nick: zaer00t                                #
#    e-mail: zaer00t@gmail.com                     #
#    www: http://creasati.com.mx                   #
#    date: 12/Septiembre/2012                      #
#    code name: creasati.com.mx                    #
#==================================================#

class AdminPageBuilder
{
	public static function header($title,$css=null, $js=null)
	{
		$ga = '';
		$fb_appid = '';

		App::load_view('admin/header', array(
			'page_title' => $title,
			'page_css' => $css,
			'page_js' => $js,
		));
	}

	public static function headerAdmin($title,$css=null, $js=null) {
		$ga = '';
		$fb_appid = '';

		App::load_view('base/Admheader', array(
			'page_title' => $title,
			'page_css' => $css,
			'page_js' => $js,
		));
	}

    public static function topbar() {
        App::load_view('base/topbar', array("", ""));
    }

    public static function topbarAdmin($activo)
    {
		App::load_view("base/Admtopbar",array("activo"=>$activo));
    }

    public static function footer() {
        App::load_view('base/footer', array("", ""));
    }

    public static function footerAdmin() {
		App::load_view('base/Admfooter', array("", ""));
	}

    public static function sidebar()
    {
		App::load_view("modulos/sidebar",array(null));
    }
}

?>
