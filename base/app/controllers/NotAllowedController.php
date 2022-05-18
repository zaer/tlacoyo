<?php
	class NotAllowedController implements Controller
	{
		public static function index($params)
		{
			App::load_view("notA",array());
		}
	}
?>