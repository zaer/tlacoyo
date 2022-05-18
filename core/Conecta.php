<?php
	class Conecta
	{
		protected $API_PUB_CONEKTA='key_IrPt83GwqWHknQwG8rxZoSg';	//TEST API
		protected $API_PRIV_CONECTA='key_ycgAK9YDy73S6f1qxzqBFA';	//TEST API

		public $json;				/* Almacena la información que es enviada CONEKTA  */
		public $monto;				// Monto total de la compra
		public $moneda;				// tipo de cambio
		public $status_pago;		// información del proceso de pago
		public $cte_info;			// datos del cte como email, telefono y nombre
		public $order_id;			// identificador unico por transaccion
		public $fecha_creada;		// fecha de creacion de la orden
		public $fecha_actualizada;	// fecha de actualización
		public $items;				// obj: Articulos y descripcion
		public $cargos;				// obj: Cargos realizados a la orden
		public $wh_log;				// obj: Log del webhook
		public $tipo;				// tipo de movimiento
		public $fecha_gen;			// fecha en que se genera
		public $mov_id;				// identificador de movimiento
		public $referencia;
		public $due_date;
		
		public $order;				// regresando el objeto al crear la orden

		public $db;					// base de datos

		public function __construct($db)
		{
			require_once("/home/zaer/www/core/libs/conekta-php/lib/Conekta.php");
			\Conekta\Conekta::setApiKey('key_ycgAK9YDy73S6f1qxzqBFA');
			\Conekta\Conekta::setApiVersion("2.0.0");
			
			$fecha_actual = date("Y-m-d");
			//sumo 3 dias
			$this->exp_date = date("d-m-Y",strtotime($fecha_actual."+ 3 days"));
			$this->oxxo_brand = APP_IMG_URL."/oxxo_brand.png";

			$this->db = $db;
			$fi = __FILE__; $fu=__FUNCTION__; $li=__LINE__;
			Util::log($fi,$fu,$li,"Inicializando constructor de conecta");
		}
		
		/*	METODO: procesaOrdenCreada(json)
		 *	Acepta de entrada el JSON que envia el API REST de conekta
		 *	se tratan la mayoria de las variables para contemplarlas y
		 *	almacenarlas en base de datos para futuras referencias.
		 *
		 *	Este es el unico momento en el cual se crea la orden, posterior
		 *	a ello son actualizaciones en base de datos
		 *
		 */
		public function ordenCreada($json_data)
		{
			$this->json	 				= json_decode($json_data);
			
			$this->monto 				= $this->json->data->object->amount;
			$this->moneda 				= $this->json->data->object->currency;
			$this->cte_info 			= json_encode($this->json->data->object->customer_info);
			$this->order_id 			= $this->json->data->object->id;
			$this->fecha_creada 		= $this->json->data->object->created_at;
			$this->fecha_actualizada 	= $this->json->data->object->updated_at;
			$this->items 				= json_encode($this->json->data->object->line_items);
			$this->wh_log 				= json_encode($this->json->webhook_logs);
			$this->mov_id 				= $this->json->id;
			$this->tipo 				= $this->json->type;
			$this->fecha_gen 			= $this->json->created_at;
			
			//$r1 = Oxxo_order::nuevo(
			//						$this->db,$order_id,
			//						$monto,$tipo,null,$fecha_actualizada,
			//						$fecha_creada,$items,$wh_log,$cte_info
			//						);
			$r1 = Oxxo_order::nuevo($this->db,$this->order_id,
									$this->monto,$this->tipo,$this->fecha_gen,
									$this->fecha_creada,$this->fecha_actualizada,
									$this->items,$this->wh_log,$this->cte_info);
			return $r1;
		}
		/*	Metodo: ordenPendiente($json)
		 *	De entrada acepta el JSON proveniente de CONEKTA para ser
		 *	tratado e identificar el proceso o por lo menos saber en que estatus
		 *	se encuentra la orden
		 */
		public function ordenPendiente($json_data)
		{
			$fi = __FILE__; $fu=__FUNCTION__; $li=__LINE__;
			/*	Cargamos los datos de la orden proveniente de conekta por 2
			 *	razones:
			 *	1.- Si la orden no existe (sino pasa por ordenCreada), creamos
			 *	la orden para ajustarla
			 *	2.- Actualiza el estaso de la orden con tiempos e información
			 *	para dar seguimiento
			 */
			$this->json 				= json_decode($json_data);
			$this->monto				= $this->json->data->object->amount;
			$this->moneda 				= $this->json->data->object->currency;
			$this->status_pago 			= $this->json->data->object->payment_status;

			$this->cte_info 			= json_encode($this->json->data->object->customer_info); // email,phone,name
			$this->order_id 			= $this->json->data->object->id;
			$this->fecha_creada 		= $this->json->data->object->created_at;
			$this->fecha_actualizada 	= $this->json->data->object->updated_at;

			$this->items 				= json_encode($this->json->data->object->line_items);
			$this->cargos 				= json_encode($this->json->data->object->charges);
			$this->wh_log				= json_encode($this->json->webhook_logs);
			$this->order_id 			= $this->json->data->object->id;
			$this->tipo 				= $this->json->type;
			$this->fecha_gen 			= $this->json->created_at;

			//buscamos si existe la orden
			$orden = Oxxo_order::buscarOrden($this->db,$this->order_id);
			if($orden !== FALSE)
			{
				/* actualizamos el status de la orden */
				$orden->setMonto($this->monto);
				$orden->setTipo($this->tipo);
				$orden->setFecha_pedido($this->fecha_creada);
				$orden->setFecha_upgrade($this->fecha_actualizada);
				$orden->setFecha_vencimiento($this->fecha_gen);
				$orden->setProducto($this->items);
				$orden->setCargo($this->cargos);
				$orden->setCliente($this->cte_info);
				$r1 = $orden->actualizar();
				Util::debug($r1,"Depurando actualizacion de ".$orden->getIdoxxo_order()." y ".$this->order_id);
			}
			else
			{
				// en caso de que no tenga nada... se crea???
				if($this->ordenCreada($json_data)){
					Util::log($fi,$fu,$li,"Se crea la orden con id ORDER: ".$this->order_id);
				}
				else{
					Util::log($fi,$fu,$li,"No fue posible crear la orden ORDER: ".$this->order_id);
					return FALSE;
				}
				Util::log($fi,$fu,$li,"El registro no existe o el ID es incorrecto: ".$this->order_id);
			}
			return TRUE;
		}
		
		public function ordenPagada($json_data)
		{
			$this->json 				= json_decode($json_data);
			$this->monto 				= $this->json->data->object->amount;
			$this->moneda 				= $this->json->data->object->currency;
			$this->status_pago 			= $this->json->data->object->payment_status;

			$this->cte_info 			= json_encode($this->json->data->object->customer_info); // email,phone,name
			$this->order_id 			= $this->json->data->object->id;
			$this->fecha_creada 		= $this->json->data->object->created_at;
			$this->fecha_actualizada 	= $this->json->data->object->updated_at;

			$this->items				= json_encode($this->json->data->object->line_items);
			$this->cargos 				= json_encode($this->json->data->object->charges);
			
			$this->referencia			= $this->json->data->object->charges->data[0]->payment_method->reference;
			$this->due_date				= $this->json->data->object->charges->data[0]->payment_method->expires_at;

			$this->wh_log 				= json_encode($this->json->webhook_logs);
			$this->order_id 			= $this->json->data->object->id;
			$this->tipo 				= $this->json->type;
			$this->fecha_gen 			= $this->json->created_at;
			
			/* metemos la información a la tabla de pagos, esta es de cargos y conceptos por asi
			 * mencionarlo pero ahora desde este punto y para tener un mejor balance
			 * cargamos la información a la tabla pagos
			 */
			//buscamos si existe la orden
			$orden = Oxxo_order::buscarOrden($this->db,$this->order_id);
			if($orden !== FALSE)
			{
				/* actualizamos el status de la orden */
				$orden->setMonto($this->monto);
				$orden->setTipo($this->tipo);
				$orden->setFecha_pedido($this->fecha_creada);
				$orden->setFecha_upgrade($this->fecha_actualizada);
				$orden->setFecha_vencimiento($this->fecha_gen);
				$orden->setProducto($this->items);
				$orden->setCargo($this->cargos);
				$orden->setCliente($this->cte_info);
				$r1 = $orden->actualizar();
				Util::debug($r1,"Actualizando a pago: ".$orden->getIdoxxo_order()." y ".$this->order_id);
				$r1=Pago::pagar($this->db,$this->order_id,date("Y-m-d H:i:s"),$this->monto,date("Y-m-d H:i:s"),'deposito',$this->referencia,1,date("Y-m-d H:i:s"),"REG: OK");
				$fi=__FILE__; $fu=__FUNCTION__; $li = __LINE__;
				Util::log($fi,$fu,$li,"Registrando pago de referencia: ".$this->order_id);
			}
			else
			{
				return FALSE;
			}
			return TRUE;
			//return $r1;
		}
		
		public function setItem($items)
		{
			/*
			$items = array(
							["name" => "Cemitas","unit_price" => 1000,"quantity" => 12],
							["name" => "Refrescos","unit_price" => 1000,"quantity" => 12],
			);
			*/
			$this->items=$items;
		}
		
		public function setCliente($nom,$email,$tel)
		{
			if($nom!=''){
				if($email!=''){
					if($tel!='')
					$this->cte_info=array(
						"name"=>$nom,
						"email"=>$email,
						"phone"=>$tel,
					);
				}
			}
		}
		
		public function crearOrden()
		{
			$thirty_days_from_now = (new DateTime())->add(new DateInterval('P30D'))->getTimestamp();
			/*
			$items = array(
							["name" => "Cemitas","unit_price" => 1000,"quantity" => 12],
							["name" => "Refrescos","unit_price" => 1000,"quantity" => 12],
			);
			*/
			/*
			$this->cte_info = array(
				"name" => "Fulanito Pérez",
				"email" => "fulanito@conekta.com",
				"phone" => "+5218181818181"
			);
			*/
			$cargos = array(
				[
					"payment_method"=>[
						"type"=>"oxxo_cash",
						"expires_at"=>$thirty_days_from_now
					]
				]
			);
			
			try{
				$this->order = \Conekta\Order::create(
												[
													"line_items" => $this->items,
													"currency" => "MXN",
													"customer_info" => $this->cte_info,
													"charges" => $cargos
													]
												);
				return $this->order;
				} catch (\Conekta\ParameterValidationError $error)
				{
					echo $error->getMessage();
				} catch (\Conekta\Handler $error)
				{
					echo $error->getMessage();
				}
		}
	}
?>
