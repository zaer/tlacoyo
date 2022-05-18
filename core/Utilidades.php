<?php
#===================================================#
#	 coded by: Moises Espindola		 _	_	#
#	 nick: zaer00t					 | |  (_)   #
#	___  _ __   ___   __ _  ___   __ _ | |_  _	#
#   / __|| '__| / _ \ / _` |/ __| / _` || __|| |   #
#  | (__ | |   |  __/| (_| |\__ \| (_| || |_ | |   #
#   \___||_|	\___| \__,_||___/ \__,_| \__||_|   #
#												  #
#	e-mail: zaer00t@gmail.com					 #
#	www: http://creasati.com.mx				   #
#	date: 12/Septiembre/2012					  #
#	code name: creasati.com.mx					#
#==================================================#
	class Utilidades extends Util
	{
		public static function sendTelegram($msg='')
        {/*
			$bot_token = '1628316873:AAGD15rYZp62V9IipA9erhxPdJvbPvu6Ww4'; //chat ID de T800-BlueDeb
			$chat_id='856800926';
			$tel = new Telegrams($bot_token,$chat_id);
			$tel->iniciar();
			$tel->sendMsg("Entrada en: INDEX Desde: {$ip}");
		*/
                        /* inicio notificaciones */
                        $bot_token = '1628316873:AAGD15rYZp62V9IipA9erhxPdJvbPvu6Ww4';
                        $chat_id='856800926';
                        $t1 = new Telegrams($bot_token,$chat_id);
						$t1->iniciar();
                        $ip=Utilidades::getIp();
                        $t1->sendMsg("Entrada desde: {$ip} [ ".$msg." ]");
                        /* fin notificaciones */
                }

	}
?>
