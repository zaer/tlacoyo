<?php
class Dominios
{
	public $dominio=null;
	public $result =null;
	public function exec_command($command)
	{
		$output = null;
		$result = exec($command, $output);
		if (!$result)
		{
			return false;
		}
		else
		{
			return implode("\n", $output);
		}
	}

	public function buscarDominio()
	{
		$this->result = $this->exec_command("whois {$this->dominio}");
		if (strpos($this->result, 'No match for domain') !== false)
		{
			return false;
		}
		else
		{
			return $this->result;
		}
	}
}
?>
