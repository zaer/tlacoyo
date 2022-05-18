<?php

	class Twitter
	{
		protected $key;
		protected $secret_key;
		protected $acces_token;
		protected $acces_token_key;

		public $cb;
		function __construct($key='',$secret='',$aT='',$aTk='')
		{
			if($key=='')
				$this->key=$key;
				
			if($secret=='')
				$this->secret_key=$secret;
				
			if($aT=='')
				$this->acces_token=$aT;
				
			if($aTk=='')
				$this->acces_token_key=$aTk;

			\Codebird\Codebird::setConsumerKey($this->key,$this->secret_key);
			$this->cb = \Codebird\Codebird::getInstance();
			$this->cb->setToken($this->acces_token,$this->acces_token_key);
		}

		public function nuevo($msg,$img='')
		{
			if($img=='')
			{
				$params = array(
							'status' => $msg,
							);
				$reply = $this->cb->statuses_update($params);
			}
			else
			{
				$params = array(
							'status' => $msg,
							'media[]' => $img
							);
				
				$reply = $this->cb->statuses_updateWithMedia($params);
			}
		}

		public function leerTweets($screen_name,$count,$retweets)
		{
			#$reply = (array) $this->cb->statuses_homeTimeline();
			#print_r($reply);

			$params = array(
							'screen_name' => $screen_name,
							'count' => $count,
							'include_rts' => $retweets,
							);
			//tweets returned by Twitter in JSON object format
			$tweets = (array) $this->cb->statuses_userTimeline($params);
			//Let's encode it for our JS/jQuery and return it
			return json_encode($tweets);
		}
	}

?>
