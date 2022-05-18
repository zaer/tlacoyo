<?php
	class html
	{
		// Almacena todos los inputs del formulario
		private $inputs = array();
		// Almacena todos los atributos del formulario
		private $form = array();
		// Este formulario tiene un valor de envio?
		private $has_submit = false;

		function __construct( $action = '', $args = false )
		{
			// todo el formulario a construir
			$this->form="";
			// Atributos por defecto del formulario
			$defaults = array(
								'action'		=> $action,
								'method'		=> 'post',
								'enctype'		=> 'application/x-www-form-urlencoded',
								'class'			=> array(),
								'id'			=> '',
								'markup'		=> 'html',
								'novalidate'	=> false,
								'add_nonce'		=> false,
								'add_honeypot'	=> true,
								'form_element'	=> true,
								'add_submit'	=> true
							);
	
			// Se mescla con los argumentos, si es que estan presentes
			if($args)
			{
				$settings = array_merge($defaults,$args);
			}	// De otro modo, utiliza los valores por defecto
			else
			{
				$settings = $defaults;
			}
	
			// Iterate through and save each option
			foreach($settings as $key => $val)
			{
				// Try setting with user-passed setting
				// If not, try the default with the same key name
				/* pendiente hasta que complete la operacion
				if( ! $this->set_att( $key, $val ) ) {
					$this->set_att( $key, $defaults[ $key ] );
				}*/
			}
		}
		
		public static function simpleModal($titulo,$contenido,$data,$tipo="sm",$idModal=null,$idBtn=null)
		{
			// construimos un modal
			/* necesitamos pasarle como argumento:
				titulo del modal
				contenido (html,php externo)
			*/
			if($idModal== null or $idModal=="")
				$idModal="blueModal";
			if($idBtn==null or $idBtn=="")
				$idBtn=".btnInfo";
			switch($tipo)
			{
				case 'md': $tipo = "-md"; break;
				case 'sm': $tipo = "-sm"; break;
				case 'lg': $tipo = "-lg"; break;
				case 'xs': $tipo = "-xs"; break;
				default: $tipo='-md'; break;
			}
			?>
			<div class="modal fade" id="<?=$idModal?>" tabindex="-1" role="dialog" aria-labelledby="blueModalLabel" aria-hidden="true">
				<div class="modal-dialog modal<?=$tipo?>" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<b><?=$titulo?></b>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<?=$contenido?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btnCerrar" data-dismiss="modal">Cerrar</button>
							<!--<button type="button" class="btn btn-primary">Save changes</button>-->
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$('<?=$idBtn?>').click(function(){
						var idInfo=$(this).attr("<?=$data?>");
						console.debug();
						console.log();
						$.ajax({
							url:'<?=$contenido?>',
							type: 'post',
							data: {idInfo:idInfo},
							success: function(response){
								$('.modal-body').html(response);
								$('#<?=$idModal?>').modal('show');
							}
						});
					});
				});
			</script>
			<?php
		}

		public function iniForm($idform,$action,$nombre)
		{
			$this->form="<form method='post' action='".$action."' class='form-horizontal' id='".$idform."'>";
			$this->form.="<fieldset>";
			$this->form.="<legend>".$nombre."</legend>";
		}
		public function finForm()
		{
			$this->form.="</fieldset><div class='col-md-4'></div><div class='col-md-4'><input type='submit' id='btnEnviar' class='btn btn-info btn-sm' value='Enviar'></div><div class='col-md-4'></div></form>";
		}

		//public static function inputForm($tipo,$nombre,$idInput,$valor,$placeholder,$ayuda)
		public function inputForm($datos)
		{
			$nombre=isset($datos["input"])?$datos["input"]:"";
			$tipo=isset($datos["type"])?$datos["type"]:"";
			$idInput=isset($datos["id"])?$datos["id"]:"";
			$valor=isset($datos["value"])?$datos["value"]:"";
			$placeholder=isset($datos["placeholder"])?$datos["placeholder"]:"";
			$ayuda =isset($datos["help"])?$datos["help"]:"";
			$req = isset($datos["required"])?"required":"";

			$this->form.="<div class='form-group'>";
			$this->form.="<label class='col-md-2 control-label' for='textinput'>".ucfirst($nombre)."</label>";
			$this->form.="<div class='col-md-6'>";
			$bq=array("_",",","."," ","-");
			$this->form.="<input id='".$idInput."' name='".Util::limpiaCadena(str_replace($bq,"",strtolower($nombre)))."' type='".$tipo."' value='".$valor."' placeholder='".$placeholder."' class='form-control input-md' ".$req.">";
			$this->form.="<span class='form help-block'>".$ayuda."</span></div></div>";
		}

		public function inputSimple($datos)
		{
			$nombre=$datos["name"];
			$tipo=$datos["type"];
			$idInput=$datos["id"];
			$valor=$datos["value"];

			$this->form.="<input id='".$idInput."' name='".strtolower($nombre)."' type='".$tipo."' value='".$valor."'>";
		}

		public function buildForm(){ return $this->form; }
		
		public static function boton($data)
		{
			$clase = array('btn btn-primary','btn btn-secondary','btn btn-success','btn btn-danger',
				'btn btn-warning','btn btn-info','btn btn-light','btn btn-dark','btn btn-outline-primary',
				'btn btn-outline-secondary','btn btn-outline-success','btn btn-outline-danger','btn btn-outline-warning',
				'btn btn-outline-info','btn btn-outline-light','btn btn-outline-dark');

			$ini="<div class='col-2'><button type='button' ";
			$btn="";
			foreach($data as $a => $b)
			{
				if($a=="label")
					$etiqueta = $b;
				else
					$btn.= $a."='".$b."' ";
			}

			$fin=">".$etiqueta."</button></div>";
			return $ini.$btn.$fin;
		}

		public static function btn($data,$tooltip)
		{
			$clase = array('btn btn-primary','btn btn-secondary','btn btn-success','btn btn-danger',
				'btn btn-warning','btn btn-info','btn btn-light','btn btn-dark','btn btn-outline-primary',
				'btn btn-outline-secondary','btn btn-outline-success','btn btn-outline-danger','btn btn-outline-warning',
				'btn btn-outline-info','btn btn-outline-light','btn btn-outline-dark');
			
			$ini="<button type='button' ";
			$btn=$etiqueta="";
			foreach($data as $a => $b)
			{
				if($a=="label")
					$etiqueta = $b;
				else
					$btn.= $a."='".$b."' ";
			}

			$fin=" data-toggle='tooltip' data-placement='top' title='".$tooltip."'>".$etiqueta."</button>";
			return $ini.$btn.$fin;
		}
	}
?>