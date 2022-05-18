<?php
	class EdoCta
	{
		private $db;
		function __construct($db){ $this->db = $db;}

		public function estado_de_cuenta($id_usr)
		{
			$ffin = date("Y-m-d H:i:s",mktime());
			// Sacamos las unidades que el usuario tenga registrada
			$vehiculos = Vehiculo::vehiculos_usuario($this->db,$id_usr);
			// Verificamos si los vehiculos tienen cargos existentes
			#Util::debug($vehiculos,__LINE__,1,1,1);
			$device=array();
			$cortes=array();
			$pagos=array();

			foreach ($vehiculos as $vehiculo)
			{
				$id_vehiculo = $vehiculo->getId();
				$device[$id_vehiculo];
				//buscamos si los vehiculos tienen cargo

				$cargo = new Cargo($this->db);
				$cargos = $cargo->cargo_vehiculo($id_vehiculo);

				if($cargos != false)
				{
					// entonces pasamos los pagos
					$pagos[$id_vehiculo]["cargo"]=$cargos;

					// calculamos el sigueinte corte
					$fini = $cargos->getFecha_corte();
					$device[$id_vehiculo]["fecha_inicial"]=$fini;
					$device[$id_vehiculo]["fecha_final"]=$ffin;
					$device[$id_vehiculo]["cargos"]=$cargos;
				}
				else
				{
					//no tiene cargo, entonces obtenemos el primer registro para comenzar con la fecha
					$device[$id_vehiculo]["cargos"]=null;
					$posicion = new Positions($this->db);
					$wow=$posicion->leer_1st_punto($id_usr,$vehiculo->getId());

					if($wow===false)
					{
						$device[$id_vehiculo]["fecha_inicial"]=date("Y-m-d h:i:s");
						$device[$id_vehiculo]["fecha_final"]=date("Y-m-d h:i:s");
					}
					else
					{
						$fini = $posicion->getDateOccurred();
						$device[$id_vehiculo]["fecha_inicial"]=$fini;
						$device[$id_vehiculo]["fecha_final"]=$ffin;
					}
				}

				// buscamos cual es el producto que esa asociado con el vehiculo
				$vp = new Vehiculo_producto($this->db);
				$vp->leer($id_vehiculo);

				$id_producto = $vp->getId_producto();
				$device[$id_vehiculo]["id_producto"]=$id_producto;

				$eventos = Positions::getEventos($this->db,$id_vehiculo,$fini,$ffin);
				#Util::debug($eventos);
				$device[$id_vehiculo]["eventos"]=$eventos;
				/* sacamos los datos del producto */
				$pro = new Producto($this->db);
				$pro->leer($id_producto);
				if($pro!=null)
				{
					$device[$id_vehiculo]["costo"] = $pro->getPrecio();
					$device[$id_vehiculo]["producto"]=$pro;
					//$device[$id_vehiculo]["id"]=null;
					$device[$id_vehiculo]["nombre"]=$vehiculo->getMarca()."-".$vehiculo->getModelo();
					$device[$id_vehiculo]["id_vehiculo"]=$id_vehiculo;
				}
			}
			return $device;
		}
	}
?>
