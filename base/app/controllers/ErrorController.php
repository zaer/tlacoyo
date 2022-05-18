<?php
	class ErrorController implements Controller
	{
		public static function index($params)
		{
			App::load_view('404',array(null));
		}
	}
?>