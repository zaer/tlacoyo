<?php
	$path_js	 = APP_ROOT_PATH."/assets/js";
	$path_assets = APP_ROOT_PATH."/assets";
	$js_files = isset($data["js"])?$data["js"]:null;

	if($js_files!=null)
	{
		if(is_array($js_files))
		{
			foreach($js_files as $stl)
			{
				if(file_exists($path_js."/".$stl))
				{
					echo "<script type='text/javascript' src='".APP_HOST_URL."/assets/js/{$stl}'></script>\n";
				}
				else
				{
					if(file_exists($path_assets."/".$stl))
					{
						echo "<script type='text/javascript' src='".APP_HOST_URL."/assets/".$stl."'></script>\n";
					}
					else{
						echo "<!-- recurso no disponible  -->";
					}
				}
			}
		}
	}
?>
