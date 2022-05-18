<?php
#===================================================#
#	 coded by: Moises Espindola		 _	_	#
#	 nick: zaer00t					 | |(_) #
#	____ __ ___ __ ____ __ _ | |__	#
# / __|| '__| / _ \ / _` |/ __| / _` || __|| | #
#| (__ | | |__/| (_| |\__ \| (_| || |_ | | #
# \___||_|	\___| \__,_||___/ \__,_| \__||_| #
#												#
#	e-mail: zaer00t@gmail.com					 #
#	www: http://creasati.com.mx				 #
#	date: 12/Septiembre/2012					#
#	code name: creasati.com.mx					#
#==================================================#
class FormUtil
{
	 public static function form_validation_rule($field, $r, $msg)
	 {
		 $rule = array();
		 $rule['field'] = $field;
		 $rule['rule'] = $r;
		 $rule['msg'] = $msg;
		 return $rule;
	 }

	 public static function validate_form($id, $rules, $confirm_msg = '', $execute_js = '', $submit = true) {
		 ?>
		<script type="text/javascript">
			function validar_forma_<?=$id?>() {
				var formaVal = new Validator("<?=$id?>");
				formaVal.clearAllValidations();

				<? foreach($rules as $rule): ?>
					formaVal.addValidation("<?=$rule['field']?>","<?=$rule['rule']?>", "<?=htmlspecialchars($rule['msg'])?>");
				<? endforeach; ?>

				if (document.<?=$id?>.onsubmit()) {
					<? if ($confirm_msg != ''){?>
							if(confirm("<?=htmlspecialchars($confirm_msg)?>")) {
								<?=$execute_js?>
								<? if ($submit): ?>document.<?=$id?>.submit(); <? endif; ?>
							}
					<? } else { ?>
							<?=$execute_js?>
							<? if ($submit): ?>document.<?=$id?>.submit();<? endif; ?>
					<? } ?>
				}
			}
		</script>
		 <?
	 }

	 public static function get_post_var($var)
	 {
	 	if (isset($_POST[$var]))
	 		return $_POST[$var];
	 	else
	 		return "";
	 }

	 public static function fileupload($nombre, $width, $label_width = null, $label = "") {
		if ($label_width != null) {
			?>
			<td style="width: <?=$label_width?>; padding-left: 25px" class="form_label"><?=$label?></td><td>
			<?
		}

		?>
		<input type="file" name="<?=$nombre?>" style="width: <?=$width?>" value="" />
		<?

		if ($label_width != null) { echo "</td>"; }
	}
	/*
	 public static function armaSelect($tabla,$id,$nombre,$class)
	 {
		#<select id="clase" name="clase" class="input-small">
		#<option>AAA</option>	#<option>AA</option>
		#<option>xxx</option>
		#</select>
		$link = conectar(_DB_);
		$sql = "select * from ".$tabla.";";
		$r1 =ExQry($sql); //utilizamos ExQry xq queremos el ID de la tabla
		#debug("Categorias",$r1,1);
		echo "<select id='".$id."' name='".$nombre."' class='{$class}' required>";
		echo "<option value='0'>N/A</option>";
		foreach($r1 as $idx => $valor)
		{
			 echo "<option value='".$idx."'>	".$valor['nombre']."</option>";
		}
		echo "</select>";
	 }*/

	/*
	 * construye un <selecte> a partir de una tabla,
	 * la tabla debe contener id y nombre como campos
	 * $tabla = tabla existente en base de datos
	 * $id = nombre de ID para DOM
	 * $nombre = nombre del select box
	 * $class = estilo CSS para el select box
	 * $val = (opcional) Setear este valor cuando se requiere cargar un valor predeterminado
	 */
	 public static function armaSelect($tabla,$id,$nombre,$val='',$dom='')
	 {
		$db = new DataBase();
		$db->connect();
		#$r1 = $db->select($tabla,'id,nombre','id>?',array(0));
		$r1 = $db->select($tabla, "id,titulo", "id>? order by id asc", array(0));
		$optionselect=$fieldx="<div class='form-group'><label class='col-md-12 control-label'>".ucfirst(str_replace("_"," ",str_replace("id_","",$nombre)))."</label>";
		#$optionselect=$fieldx="<div class='form-group'><div class='col-md-12'>";
		$optionselect.="<select id='".$id."' name='".$nombre."' class='form-control' ".$dom." cols='15' required>";
		$valores='<option value="0">Seleccione una opcion</option>';
		$item='';

		if((count($r1)>0) and $r1!=null)
		{
			 foreach($r1 as $idx => $valor)
			 {
					#Util::debug($idx,1,1,1);
					//$no = $idx+1;
					$no = $valor["id"];
					if($val==$no) $item='selected';else $item='';
					$valores.='<option value="'.$no.'" '.$item.'>'.$valor['titulo'].'</option>';
			 }
		}
		else
		{
			 return null;
		}
		return $optionselect.$valores."</select></div>";
	 }

	public static function armaSelect2($tabla,$id,$nombre,$val='',$dom='',$id_usuario)
	 {
		$db = new DataBase();
		$db->connect();
		#$r1 = $db->select($tabla,'id,nombre','id>?',array(0));
		$r1 = $db->select($tabla, "id,nombre", "id>? and FK_USERS_ID=? order by id asc", array(0,$id_usuario));
		$optionselect=$fieldx="<div class='form-group'><label class='col-md-4 control-label'>".ucfirst(str_replace("_"," ",str_replace("id_","",$nombre)))."</label><div class='col-md-12'>";
		#$optionselect=$fieldx="<div class='form-group'><div class='col-md-12'>";
		$optionselect.="<select id='".$id."' name='".$nombre."' class='form-control' ".$dom." required>";
		$valores='<option value="0">Seleccione una opcion</option>';
		$item='';

		if((count($r1)>0) and $r1!=null)
		{
			 foreach($r1 as $idx => $valor)
			 {
					#Util::debug($idx,1,1,1);
					//$no = $idx+1;
					$no = $valor["id"];
					if($val==$no) $item='selected';else $item='';
					$valores.='<option value="'.$no.'" '.$item.'>'.$valor['nombre'].'</option>';
			 }
		}
		else
		{
			 return null;
		}
		return $optionselect.$valores."</select></div></div>";
	 }
	 /*
	 * construye un <selecte> dependiente de un select primario
	 * la tabla debe contener id y nombre como campos
	 * $tabla = tabla existente en base de datos
	 * $no = el ID de referencia para armar el select
	 * $id = nombre de ID para DOM
	 * $nombre = nombre del select box
	 * $class = estilo CSS para el select box
	 */
	 public static function armaDSelect($tabla,$no,$id,$nombre,$class,$val='')
	 {
		$db = new DataBase();
		$db->connect();

		#$r1 = $db->select($tabla,'id,nombre','id>?',array(0));
		$r1 = $db->select($tabla, "id,id_marca,nombre", "id_marca=?", array($no));
		$optionselect="<select id='".$id."' name='".$nombre."' class='{$class}' required>";
		$valores='';
		$item='';
		if((count($r1)>0) and $r1!=null)
		{
			 foreach($r1 as $idx => $valor)
			 {
					#Util::debug('valor linea 281',$valor,1,1);
					if($val==$valor['id'])$item='selected'; else $item='';
					$valores.='<option value="'.$valor['id'].'" '.$item.'>'.$valor['nombre'].'</option>';
			 }
		}
		else
		{
			 return null;
		}
		return $optionselect.$valores."</select>";
	 }
	 /*
	* metodo para generar un select box con una lista determinada
	* $desde = valor inicial
	* $hasta = valor final
	* $desde siempre debe ser menor a $hasta
	*/
	 public static function armaSelectAnio($desde,$hasta,$class,$val='')
	 {
		$optionselect="<select id='anio' name='anio' class='{$class}' required>";
		$valores='';$item='';
		for(;$desde<=$hasta;$hasta--)
		{
			 if($val==$hasta)$item='selected'; else $item='';
			 $valores.='<option value="'.$hasta.'" '.$item.'>'.$hasta.'</option>';
		}
		return $optionselect.$valores."</select>";
	 }

	 public static function armaForm()
	 {
		/*FormUtil::get_post_var(1)*/
		$codigo='';
		foreach($_REQUEST as $val => $x)
		{
			 $codigo.='$'.$val.'=FormUtil::get_post_var("'.$val.'");<br>';
		}
		return $codigo;
	 }

	 /* funcion para construir un formulario a partir de una tabla en base de datos */
	 public static function buildForm($db,$tabla)
	 {
		$sql = "show full columns from ".$tabla;

		$r1 = $db->execute($sql);
		$result = $db->fArray($r1);

		$r1->close();
		$data=array();
		$fieldx='';
		foreach($result as $field => $key)
		{#Util::debug($key);
			 $acciones = explode(",",$key['Comment']);
			 if(!($acciones[0]=='no' || $acciones[0]=='No'))
			 {
					switch($acciones[0])
					{
						case 'txt':
							preg_match_all("/(\d{1,3})/x",$key['Type'],$lon);
							$lon = (int)current($lon[0]);
							//si la longitud del texto es mayor a 50 entonces creamos un textarea
							if($lon > 300)
							{
								$fieldx .= "<div class='form-group'>
								<label class='control-label'>".str_replace("_"," ",ucfirst($key['Field']))."</label>
								<textarea class='form-control' value='' name='".$key['Field']."' id='".$key['Field']."' placeholder=''></textarea>
								</div>";
							}
							else
							{
								$fieldx.="<div class='form-group'>
								<label class='control-label'>".str_replace("_"," ",ucfirst($key['Field']))."</label>
								<input type='text' style='width:280px;' class='form-control' value='' id='".$key['Field']."' name='".$key['Field']."' placeholder='' min='1' maxlength='".$lon."' required>
								</div>";
							}
							break;
						case 'pwd':
							preg_match_all("/(\d{1,3})/x",$key['Type'],$lon);
							$lon = (int)current($lon[0]);
							//si la longitud del texto es mayor a 50 entonces creamos un textarea
							$fieldx.="<div class='form-group'><label class='control-label'>".ucfirst($key['Field'])."</label>
							<input id='".$key['Field']."' type='password' style='width:280px;' class='form-control' value='' name='".$key['Field']."' placeholder='' min='1' maxlength='".$lon."'>
							</div>";
							break;
						case 'select':
							if($acciones[1]=='foranea')
							{
								//creamos el select a partir de la tabla seteada en @acciones[2]
								$fieldx.=FormUtil::armaSelect($acciones[2],$key['Field'],$key['Field'],'form-control');
							}
							break;
						case 'xselect':
							$optionselect="<div class='form-group'><div class='col-md-12'>";
							$optionselect.="<select id='xselect' name='xselect' class='form-control' required>";
							$valores='<option value="0">Seleccione una opcion</option>';
							$item='';

							$at = $acciones;
							unset($at[0]);
							foreach ($at as $t)
							{
								$valores.='<option value="'.strtolower($t).'">'.$t.'</option>';
							}
							$fieldx .= $optionselect.$valores."</select></div></div>";
							break;
						case 'file':
							$fieldx.="<div class='form-group'>
							<label class='control-label'>".str_replace("_"," ",ucfirst($key['Field']))."</label>
							<input id='".$key['Field']."' type='file' class='form-control' value='' name='".$key['Field']."' placeholder='' style='width:280px;' required>
							</div>";
							break;
						case 'files':
							$fieldx.="<div class='form-group'>
							<label class='control-label'>".str_replace("_"," ",ucfirst($key['Field']))."</label>
							<input id='".$key['Field']."' type='file' class='form-control' value='' name='".$key['Field']."[]' multiple='true' placeholder='' style='width:280px;' required>
							</div>";
							break;
						case 'int':
							$fieldx.="<div class='form-group'>
							<label class='control-label'>".ucfirst($key['Field'])."</label>
							<input id='".$key['Field']."' type='number' class='form-control' value='' name='".$key['Field']."' placeholder='' required></div>";
							break;
						case 'text':
							$fieldx .= "<div class='form-group'>
							<label class='control-label' id='txtEditor'>".ucfirst($key['Field'])."</label>
							<textarea id='estesi' class='form-control' value='' name='".$key['Field']."' placeholder=''></textarea>
							</div>";
							break;
						case 'chk':
							$fieldx .= "<div class='form-group'>
							<label class='control-label'>".ucfirst($key['Field'])."</label>
							<input id='".$key['Field']."' class='form-control' type='checkbox' name='".$key['Field']."' value='1' required>
							</div>";
							break;
						case 'date':
							$fieldx .= "<div class='form-group'>
							<label class='control-label'>".str_replace("_"," ",ucfirst($key['Field']))."</label>
								<input style='width:280px;' type='date' id='".$key['Field']."' class='form-control' name=".$key['Field']." />
							</div>";
							break;
						default:
						break;
					}
			 }
		}
		if($fieldx=='' or $fieldx==NULL) return false;
		return $fieldx;
	 }
}
?>
