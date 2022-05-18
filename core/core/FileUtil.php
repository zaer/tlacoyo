<?php
	define("APP_FILEUPLOAD_OK", 0);
	define("APP_FILEUPLOAD_ERROR_SIZE", 1);

	class FileUtil
	{
		public static function is_uploaded($field_id)
		{
			if (empty($_FILES))
			{
				return false;
			}
			return is_uploaded_file($_FILES[$field_id]['tmp_name']);
		}

		public static function gFoto($field_id, $dest_path, $size_limit)
		{
			if($_FILES[$field_id]["size"] < ($size_limit * 1024))
			{
				if ($_FILES[$field_id]["error"] > 0)
				{
					return -1;
				}
				else
				{
					if(file_exists($dest_path))
					{
						//verificamos los permisos de la carpeta destino
						$perms = (int)decoct(fileperms($dest_path) & 0777);
						$archivo = $dest_path."/".$_FILES[$field_id]["name"];

						if($perms>700)
						{
							if(move_uploaded_file($_FILES[$field_id]["tmp_name"], $archivo))
							{
								#return APP_IMG_URL."/personal/".$_FILES[$field_id]["name"];
								return $_FILES[$field_id]["name"];
							}
							else
							{
								return -3;
							}
						}
						else
						{
							return -2;
						}
					}
					else
					{
						// no existe el directorio, entonces lo creamos
						if(mkdir($dest_path,0777,true))
						{
							FileUtil::gFoto($field_id,$dest_path,$size_limit);
						}
						else
						{
							return -4;
						}
					}
				}
			}
			else
			{
				return 0;
			}
		}

		public static function save_file($field_id, $dest_path, $size_limit)
		{
			if ($_FILES[$field_id]["size"] < ($size_limit * 1024))
			{
				if ($_FILES[$field_id]["error"] > 0)
				{
					throw new AppException("Error al subir archivo [".__LINE__."]-[".__METHOD__."] " . $_FILES[$field_id]["name"] . ": " . $_FILES[$field_id]["error"]);
				}
				else
				{
					if(!move_uploaded_file($_FILES[$field_id]["tmp_name"], $dest_path))
					{
						throw new AppException("Error al subir archivo [".__LINE__."]-[".__METHOD__."]" . $_FILES[$field_id]["name"]);
					}
					return APP_FILEUPLOAD_OK;
				}
			}
			else
			{
				return APP_FILEUPLOAD_ERROR_SIZE;
			}
		}
		/* ++ mi funcion multi ++*/
		public static function save_filem($field_id,$dest_path,$size)
		{
			$archivos = count($_FILES[$field_id]['name']);
			for($i=0; $i<$archivos; $i++)
			{
				if ($_FILES[$field_id]["size"][$i] < ($size * 1024))
				{
					if ($_FILES[$field_id]["error"][$i] > 0)
					{
						throw new AppException("Error al subir archivo -> " . $_FILES[$field_id]["name"][$i] . ": " . $_FILES[$field_id]["error"][$i]);
					}
					else
					{
						if(!move_uploaded_file($_FILES[$field_id]["tmp_name"][$i], $dest_path))
						{
							throw new AppException("Error al subir archivo = " . $_FILES[$field_id]["name"][$i]);
						}
					}
				}
				else
				{
					return APP_FILEUPLOAD_ERROR_SIZE;
				}
			}
			return APP_FILEUPLOAD_OK;
		}

		static function get_file_ext($idName)
		{
			$name = $_FILES[$idName]['name'];
			$arr = explode(".", $name);
			$ext = strtolower($arr[count($arr)-1]);
			return $ext;
		}

		/**
		 * Returns the size of a file without downloading it, or -1 if the file
		 * size could not be determined.
		 * @param $url - The location of the remote file to download. Cannot
		 * be null or empty.
		 *
		 * @return The size of the file referenced by $url, or -1 if the size
		 * could not be determined.
		 */
		public static function get_file_size($url)
		{
			// Assume failure.
			$result = -1;

			$curl = curl_init($url);

			// Issue a HEAD request and follow any redirects.
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			#curl_setopt($curl, CURLOPT_USERAGENT, get_user_agent_string());

			$data = curl_exec($curl);
			curl_close($curl);

			if($data)
			{
				$content_length = "unknown";
				$status = "unknown";

				if( preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches))
				{
					$status = (int)$matches[1];
				}
				if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) )
				{
					$content_length = (int)$matches[1];
				}
				// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
				if( $status == 200 || ($status > 300 && $status <= 308) )
				{
					$result = $content_length;
				}
			}
			return $result;
		}

		public static function upFile($field_id,$directorio,$size_limit)
		{
			if(!is_dir(APP_DOCS_PATH."/".$directorio))
			{
				if(!mkdir(APP_DOCS_PATH."/".$directorio,0777,true))
				{
					throw new AppException("No se puede crear el directorio <b>".str_replace("//","/",APP_DOCS_PATH)."/".$directorio."</b>");
				}
			}
			if ($_FILES[$field_id]["size"] < ($size_limit * 1024))
			{
				if ($_FILES[$field_id]["error"] > 0)
				{
					throw new AppException("Error al subir el archivo " . $_FILES[$field_id]["name"] . ": " . $_FILES[$field_id]["error"]);
				}
				else
				{
					$nombre=str_replace(" ","",$_FILES[$field_id]["name"]);
					if(!move_uploaded_file($_FILES[$field_id]["tmp_name"], APP_DOCS_PATH."/".$directorio."/".$nombre))
					{
						echo APP_DOCS_PATH."/".$directorio;
						throw new AppException("Error al subir el archivo " . $_FILES[$field_id]["name"]);
					}
					#return APP_FILEUPLOAD_OK;
					return $nombre;
				}
			}
			else
			{
				return APP_FILEUPLOAD_ERROR_SIZE;
			}
		}

		public static function upArchivo($field_id,$directorio,$size_limit)
		{
			if(!is_dir($directorio))
			{
				if(!mkdir($directorio,0777,true))
				{
					throw new AppException("No se puede crear el directorio <b>".$directorio."</b>");
				}
			}
			if ($_FILES[$field_id]["size"] < ($size_limit * 1024))
			{
				if ($_FILES[$field_id]["error"] > 0)
				{
					throw new AppException("Error al subir el archivo " . $_FILES[$field_id]["name"] . ": " . $_FILES[$field_id]["error"]);
				}
				else
				{
					$nombre=str_replace(" ","",$_FILES[$field_id]["name"]);
					if(!move_uploaded_file($_FILES[$field_id]["tmp_name"],$directorio."/".$nombre))
					{
						echo $directorio;
						throw new AppException("Error al subir el archivo " . $_FILES[$field_id]["name"]);
					}
					#return APP_FILEUPLOAD_OK;
					return $nombre;
				}
			}
			else
			{
				return array("Response","El archivo es demasiado grande");
			}
		}
	}

?>
