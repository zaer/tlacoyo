<?php
include '/home/zaer/www/core/libs/Telegram/Telegram.php';
class Telegrams extends Telegram
{
  private $token;
  private $chat_id;
  public $tier;
  private $req;

  public function __construct($token,$chat_id)
  {
    $this->token=$token;
    $this->chat_id=$chat_id;
    $this->iniciar();
  }

  public function iniciar()
  {
    $this->tier = new Telegram($this->token);
    $this->req = $this->tier->getUpdates();
  }

  public function sendMsg($msg)
  {
  	$resumen = ["chat_id"=>$this->chat_id,"text"=>$msg];
  	$this->tier->sendMessage($resumen);
  }
}
?>
