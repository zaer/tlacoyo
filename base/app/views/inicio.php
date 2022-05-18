<?php
	/* ini header */
	$css=$js=null;
	$css=array('bootstrap.css','style.css','font-awesome.css','SidebarNav.min.css','custom.css','owl.carousel.css');
	$js =array('jquery-1.11.1.min.js','modernizr.custom.js','Chart.js','metisMenu.min.js','custom.js','pie-chart.js','owl.carousel.js');

	$title=$description=$keywords=null;
	$title='Plaza ONE';
	$title='';
	$keywords="";
	
	PageBuilder::header(null,null,null,$css,$js);
	App::load_widget("menu",array());
	App::load_view("topbar",array("titulo"=>"Titulo"));
	// contenido
		$js=array('amcharts.js','serial.js','export.min.js','export.css','light.js','index1.js','Chart.bundle.js','classie.js','utils.js','jquery.nicescroll.js','scripts.js','SidebarNav.min.js','SimpleChart.js','bootstrap.js');
	App::load_view("footer",array("js"=>$js));
?>
</body>
</html>
