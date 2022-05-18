<?php
	require_once __DIR__.'/libs/MPago/vendor/autoload.php'; // You have to require the library from your Composer vendor folder

	class MPago
	{
		private $token;
		private $card_token;
		private $metodo;
		private $public_key='TEST-954deefe-e961-4ac7-94b2-5754a7e7fc6b';
		private $acces_token='TEST-6501269206385449-051815-26d1130a8564d22e40bbf08264995979-454356266';

		public function __construct()
		{
			// seteamos los datos base que ya veremos
			MercadoPago\SDK::setAccessToken($this->acces_token); // Either Production or SandBox AccessToken
			$payment = new MercadoPago\Payment();
			$payment->transaction_amount = 141;
			$payment->token = $this->public_key;
			$payment->description = "Ergonomic Silk Shirt";
			$payment->installments = 1;
			$payment->payment_method_id = "visa";
			$payment->payer = array(
									"email" => "larue.nienow@email.com"
									);
			$payment->save();
			echo $payment->status;
		}
/*
		MercadoPago\SDK::setAccessToken("YOUR_ACCESS_TOKEN"); // Either Production or SandBox AccessToken
		$payment = new MercadoPago\Payment();
		$payment->transaction_amount = 141;
		$payment->token = "YOUR_CARD_TOKEN";
		$payment->description = "Ergonomic Silk Shirt";
		$payment->installments = 1;
		$payment->payment_method_id = "visa";
		$payment->payer = array(
		"email" => "larue.nienow@email.com"
		);
		$payment->save();
		echo $payment->status;
*/
	}
?>
