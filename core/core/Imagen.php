<?php

	class Imagen
	{
		//metodo para guardar la imagen
		public static function guarda_imagen($file_id,$titulo,$directorio,$w='',$h='')
		{
			//checamos si se paso la imagen por post multipart
			if(FileUtil::is_uploaded($file_id))
			{
				$cadena = (str_replace(' ', '', $titulo));
				$cadena = Utilidades::limpiaCadena($cadena);
				$nombre_archivo = $cadena . "_" . uniqid() . '.jpg';

				$tmp_name = Util::gen_rand_str();

				if(!is_dir(APP_IMG_PATH."/".$directorio))
				{
					# si el directorio no existe
					# creamos el directorio

					if(!mkdir(APP_IMG_PATH."/".$directorio,0777,true))
					{
						throw new AppException("Ocurrio un erro al crear el directorio: <b>{$directorio}</b>");
					}
				}

				FileUtil::save_file($file_id, APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name, 20000);
				Utilidades::setMarca(APP_IMG_PATH."/".$directorio."/".$tmp_name,70,0,0);
				$image = new ImageEdit();
				$thumb = new ImageEdit();

				$image->load(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);

				$thumb->load(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);
				#$thumb->resize(400,300);
				$thumb->resizeToWidth(200);
				$thumb->save(APP_IMG_PATH .'/'.$directorio.'/'.str_ireplace(".jpg","_thumb.jpg",$nombre_archivo),IMAGETYPE_JPEG, 90);

				if($w!='' and $h!='')$image->resize($w,$h);
				if($w!='')$image->resizeToWidth($w);
				if($h!='')$image->resizeToHeight($h);

				$image->save(APP_IMG_PATH . '/'.$directorio.'/' . $nombre_archivo,IMAGETYPE_JPEG, 80);
				unlink(APP_IMG_PATH . '/'.$directorio.'/' . $tmp_name);
				return $nombre_archivo;
			}
			else
			{
				return "";
			}
		}

		//metodo para guardar multiples imagenes
		public static function guardar_multi_imagen($file_id,$titulo,$directorio,$w='',$h='')
		{
			$size=20000;
			$archivos = count($_FILES[$file_id]['name']);
			if($archivos > 1)
			{
				$fotos = array();
				for($i=0; $i<$archivos; $i++)
				{
					if ($_FILES[$file_id]["size"][$i] < ($size * 1024))
					{
						if ($_FILES[$file_id]["error"][$i] > 0)
						{
							throw new AppException("Error al subir multiples archivos" . $_FILES[$file_id]["name"][$i] . ": " . $_FILES[$file_id]["error"][$i]);
						}
						else
						{
							$cadena=str_replace(' ', '', $titulo);
							$cadena = Utilidades::limpiaCadena($cadena);
							$nombre_archivo = $cadena . "_" . uniqid() . '.jpg';
							$tmp_name = Util::gen_rand_str();
							if(!is_dir(APP_IMG_PATH."/".$directorio))
							{
								# si el directorio no existe
								# creamos el directorio
								if(!mkdir(APP_IMG_PATH."/".$directorio,0777,true))
								{
									throw new AppException("Ocurrio un erro al crear el directorio {$directorio}");
								}
							}

							if(!move_uploaded_file($_FILES[$file_id]["tmp_name"][$i], APP_IMG_PATH."/{$directorio}/".$tmp_name))
							{
								throw new AppException("Error al subir archivo >>" . $_FILES[$file_id]["name"][$i]);
							}
							$image = new ImageEdit();
							$image->load(APP_IMG_PATH . "/{$directorio}/" . $tmp_name);

							if($w!='' and $h!='')$image->resize($w,$h);
							if($w!='')$image->resizeToWidth($w);
							if($h!='')$image->resizeToHeight($h);

							$image->save(APP_IMG_PATH . "/{$directorio}/" . $nombre_archivo,IMAGETYPE_JPEG, 80);
							Utilidades::setMarca(APP_IMG_PATH."/".$directorio."/".$nombre_archivo,30,0,0);
							unlink(APP_IMG_PATH . "/{$directorio}/" . $tmp_name);
							$fotos[]= $nombre_archivo;
						}
					}
					else
					{
						return APP_FILEUPLOAD_ERROR_SIZE;
					}
				}
				return implode("|",$fotos);
			}
			else
			{
				echo "Error: Se debe cargar mas de un archivo";
			}
		}


		public static function thumb($imagen)
		{
			$thumb = new ImageEdit();
			$thumb->load($imagen);

			$thumb->resizeToWidth(250);
			#$thumb->save(APP_IMG_PATH .'/'.$directorio.'/'.str_ireplace(".jpg","_thumb.jpg",$nombre_archivo),IMAGETYPE_JPEG, $zip);
			$thumb->save(str_ireplace(".png","_thumb.png",$imagen),IMAGETYPE_PNG, 75);
		}
	}
?>
