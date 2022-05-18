<?php
#===================================================#
#     coded by: Moises Espindola         _    _    #
#     nick: zaer00t                     | |  (_)   #
#    ___  _ __   ___   __ _  ___   __ _ | |_  _    #
#   / __|| '__| / _ \ / _` |/ __| / _` || __|| |   #
#  | (__ | |   |  __/| (_| |\__ \| (_| || |_ | |   #
#   \___||_|    \___| \__,_||___/ \__,_| \__||_|   #
#                                                  #
#    e-mail: zaer00t@gmail.com                     #
#    www: http://creasati.com.mx                   #
#    date: 12/Septiembre/2012                      #
#    code name: creasati.com.mx                    #
#==================================================#

define("PAGO_STATUS_UNDEFINED", 0);
define("PAGO_STATUS_CAPTURING_DATA", 1);
define("PAGO_STATUS_NEW", 2);
define("PAGO_STATUS_FX_CONVERTED", 101);
define("PAGO_STATUS_VERIFIED", 102);
define("PAGO_STATUS_SUBMITTED", 103);
define("PAGO_STATUS_APPROVED", 4);
define("PAGO_STATUS_DECLINED", 6);
define("PAGO_STATUS_ERROR", 104);
define("PAGO_STATUS_PENDING", 7);
define("PAGO_STATUS_EXPIRED", 5);
define("PAGO_STATUS_FIRMA_INVALIDA", 8);

define("PAYU_MERCHANT_ID", "508139");
define("PAYU_MERCHANT_ACCOUNT", "509233");
define("PAYU_API_KEY", "39qq3m0lvbdupvmmlfscbmc94c");
define("PAYU_CURRENCY", "MXN");

class PayU {

    public static $PAGO_STATUS_NAMES = array(
        0 => "Sin Definir",
        1 => "Pendiente (Capturando Datos)",
        2 => "Pendiente (Pago Nuevo)",
        101 => "Pendiente (Conversion de Moneda)",
        102 => "Pendiente (Verificado Antifraude)",
        103 =>"Pendiente (Enviado a proveedor de pago)",
        4 => "Aprobado",
        6 => "Declinado",
        104 => "Error",
        7 => "Pendiente",
        5 => "Expirado",
        8 => 'Error (Firma Invalida)'
    );

    private $id;
    private $id_payu;
    private $id_registro;
    private $fecha;
    private $status;
    private $metodo_pago;
    private $monto;
    private $form;

    public static function validar_firma($reference_code, $amount) {
        $firma = PAYU_API_KEY . '~' . PAYU_MERCHANT_ID . '~' . $reference_code . '~' . $amount . '~' . PAYU_CURRENCY;
        return md5($firma);
    }

    /** @var DataBase */
    private $db;

    /*
     * Funciones PayU
     */
    public function __construct($db) {
        $this->db = $db;
    }

    private static function crear($db, $id, $id_payu, $id_registro, $fecha, $status, $metodo_pago, $monto, $form) {
         $dto = array();
         $dto["id"] = $id;
         $dto["id_payu"] = $id_payu;
         $dto["id_registro"] = (int)$id_registro;
         $dto["fecha"] = $fecha;
         $dto["status"] = (int)$status;
         $dto["metodo_pago"] = (int)$metodo_pago;
         $dto["monto"] = (double)$monto;
         $dto['form'] = base64_encode($form);

         return $db->insert('pv_pagos', $dto);
    }

    public function generar($id_registro, $monto) {
        $id = substr(str_replace(".", "", strtoupper(uniqid("", true))), 0, 20);
        PayU::crear($this->db, $id, "", $id_registro, DateUtil::now(), PAGO_STATUS_UNDEFINED, "", $monto, '');
        return $id;
    }

    public static function generarLinkPago($db, $idRegistro, $monto, $concepto, $email) {
        $pago = new PayU($db);
        $concepto = htmlentities($concepto);
        $monto = (double)$monto;
        $trxid = $pago->generar($idRegistro, $monto);
        $monto = number_format($monto, 2, '.', '');
        $form = "<form id='forma_pago' method='POST' action='https://gateway.payulatam.com/ppp-web-gateway/'> <input type='hidden' name='merchantId' value='" . PAYU_MERCHANT_ID  . "'> <input type='hidden' name='referenceCode' value='$trxid'> <input type='hidden' name='description' value='$concepto'> <input type='hidden' name='amount' value='$monto'> <input type='hidden' name='tax' value='0'> <input type='hidden' name='taxReturnBase' value='0'> <input type='hidden' name='signature' value='" . self::validar_firma($trxid, $monto) . "'> <input type='hidden' name='accountId' value='" . PAYU_MERCHANT_ACCOUNT . "'> <input type='hidden' name='currency' value='" . PAYU_CURRENCY . "'> <input type='hidden' name='buyerEmail' value='$email'><input type='hidden' name='algorithmSignature' value='MD5'> </form>";
        $db->update('pv_pagos', array('form' => base64_encode($form)), 'id=?', array($trxid));
        return APP_HOST_URL . '/payu/' . $trxid;
    }

    public function leer($id) {
        $id = strtoupper($id);

        $res = $this->db->select('pv_pagos', "*", "id=?", array($id));

        if (count($res) > 0) {
            $dto = $res[0];
            $this->id = $id;
            $this->id_payu = $dto["id_payu"];
            $this->fecha = $dto["fecha"];
            $this->id_registro = (int)$dto["id_registro"];
            $this->metodo_pago = $dto["metodo_pago"];
            $this->monto = (double)$dto["monto"];
            $this->status = (int)$dto["status"];
            $this->form = base64_decode($dto['form']);
            return true;
        } else {
            return false;
        }
    }

    public static function leer_todos($db, $id_registro) {
        $res = $db->select('pv_pagos', 'id', 'id_registro=?', array($id_registro));
        $pagos = array();

        if (is_array($res) && count($res) > 0) foreach($res as $row) {
            $id = $row['id'];
            $pago = new PayU($db);
            $pago->leer($id);
            $pagos[] = $pago;
        }

        return $pagos;
    }

    public function actualizar_metodo_pago($metodo_pago) {
        $this->db->update('pv_pagos', array('metodo_pago' => $metodo_pago), "id=?", array($this->id));
    }

    public function actualizar($id_payu, $monto, $status) {
        $status_viejo = $this->status;
        $this->monto = $monto;
        $this->status = $status;
        $this->id_payu = $id_payu;

        $data = array();
        $data["monto"] = (double)$monto;
        $data["status"] = (int)$status;
        $data["id_payu"] = $id_payu;

        $this->db->update('pv_pagos', $data, "id=?", array($this->id));

        if ($status_viejo != PAGO_STATUS_APPROVED && $status == PAGO_STATUS_APPROVED) {
            $this->db->update('pv_inscripciones', array('pagado' => 1, 'fecha_pago' => time()), 'id=?', array($this->id_registro));

            $datos = $this->db->select('pv_inscripciones', 'nombre, apaterno, amaterno, email', 'id=?', array($this->id_registro));

            if(count($datos)>0) {
                $datos = $datos[0];
                RegistroPueblaVeracruz::enviar_email_confirmacion($datos['nombre'] . ' ' . $datos['apaterno'], $datos['email']);
            }
        }
    }

    /*
     * Getters y Setters
     */

    public function getId() {
        return $this->id;
    }

    public function getId_payu() {
        return $this->id_payu;
    }

    public function getId_registro() {
        return $this->id_registro;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getMetodo_pago() {
        return $this->metodo_pago;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function getForm() {
        return $this->form;
    }
}

?>
