<?php
	require APP_CLASS_PATH."libs/qrgen/phpqrcode.php";
	require APP_CLASS_PATH."libs/qrgen/class-qrcdr.php";
	require APP_CLASS_PATH."libs/qrgen/functions.php";

	class QRGen
	{
		private $outdir;
		private $optionlogo;
		private $optionstyle;
		private $backcolor;
		private $frontcolor;
		private $level;
		private $size;
		private $url;
		private $code;
		private $res;

		public function __construct()
		{
			$this->optionstyle = "3d"; //filter_input(INPUT_POST, "style", FILTER_SANITIZE_STRING);
			$this->backcolor = hexdec(str_replace('#', '0x', "#ffffff"));
			$this->frontcolor = hexdec(str_replace('#', '0x', "#000000"));
			$this->level="Q";
			$this->size=20;
		}

		public function generar($par)
		{
			if(in_array($this->level, array('L','M','Q','H')))
			{
				$errorCorrectionLevel = $this->level;
			}
			if($this->size)
			{
				$matrixPointSize = min(max((int)$this->size, 4), 32);
			}

			$filenamepng = $this->outdir."/".$par.".png"; // el nombre del PNG debe cambiar // toda la ruta
			QRcdr::png(
				$this->url.$par,
				$filenamepng,
				$errorCorrectionLevel,
				$matrixPointSize,
				2,
				false,
				$this->backcolor,
				$this->frontcolor,
				$this->optionstyle
			);
			
			$finalpng = basename($filenamepng); //solo el nombre del archivo
			$mergedimage = false;

			if($this->optionlogo)
			{
				$mergedimage = mergeImages($this->outdir."/".$finalpng,$this->optionlogo,false);
			}

			if($mergedimage){
				$placeholder = $this->outdir."/".$mergedimage;
			} else {
				$placeholder = $this->outdir."/".$finalpng;
			}

			$result = array(
				'png'=> $finalpng, 
				'placeholder'=> $placeholder, 
				'optionlogo'=> $this->optionlogo,
		    );
			$this->res = json_encode($result);
		}

		public function mostrar(){
			return $this->res;
		}
		
		public function setOutdir($dato){ $this->outdir=$dato; }
		public function setLogo($dato){ $this->optionlogo=$dato; }
		public function setOptionstyle($dato){ $this->optionstyle=$dato; }
		public function setBackcolor($dato){ $this->backcolor=$dato; }
		public function setFrontcolor($dato){ $this->frontcolor=$dato; }
		public function setLevel($dato){ $this->level=$dato; }
		public function setSize($dato){ $this->size=$dato; }
		public function setUrl($dato){ $this->url=$dato; }
		public function setCode($dato){ $this->code=$dato; }
		public function setRes($dato){ $this->res=$dato; }
	}
?>