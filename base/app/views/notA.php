<?php
	$js=array();
	PageBuilder::header('Unidades registradas','','','css',$js);
	PageBuilder::topbar("unidades","Plataforma GPS");

	App::load_widget("btopbar",array(null));
	$titulo = $data["titulo"];
	if($titulo=='')
		$titulo = "Zona restringida";
?>
<div class="container-fluid" style="font-size: 12px">
	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-default panel-table">
						<div class="panel-heading">
							<div class="row">
								<div class="col col-xs-6 text-center">
									<h3 class="panel-title" style="color:crimson;"><?=$titulo?></h3>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<?php
								Util::msg(
									"Acceso restringido / Restricted Area",
									"No cuenta con los permisos necesarios para ingresar a este modulo",
									"Para mayor Información, contactar sistema administrativo BlueDeb"
								);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	App::load_view("footer",array(null));
?>
</body>
</html>