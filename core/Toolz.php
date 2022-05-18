<?php
class Toolz
{
	public static function inHttp()
	{
		if (function_exists('exec'))
		{
			$www_total_count = 0;
			@exec ('netstat -an | egrep \':80|:443\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);

			foreach ($results as $result)
			{
				$array = explode(':', $result);
				$www_total_count ++;

				if (preg_match('/^::/', $result))
				{
					$ipaddr = $array[3];
				}
				else
				{
					$ipaddr = $array[0];
				}

				if(!in_array($ipaddr, $unique))
				{
					$unique[] = $ipaddr;
					$www_unique_count ++;
				}
			}
			unset ($results);
			return count($unique);
		}
	}

	public static function inGps($port)
	{
		if (function_exists('exec'))
		{
			$www_total_count = 0;
			@exec ('netstat -an | egrep \':'.$port.'\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);

			foreach ($results as $result)
			{
				$array = explode(':', $result);
				$www_total_count ++;

				if (preg_match('/^::/', $result))
				{
					$ipaddr = $array[3];
				}
				else
				{
					$ipaddr = $array[0];
				}

				if(@!in_array($ipaddr, $unique))
				{
					$unique[] = $ipaddr;
					$www_unique_count ++;
				}
			}
			unset ($results);

			if(function_exists("is_countable"))
			{
				if(is_countable($unique))
				{
					return count($unique);
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return count($unique);
			}
		}
	}

	/*
		Get server memory usage
		This function returns the server memory usage as a percentage:
	*/
	function server_memory_usage()
	{
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = $mem[2] / $mem[1] * 100;
		return $memory_usage;
	}

	/*
		Get current disk usage
		This function returns the amount of disk usage as a percentage:
	*/
	function disk_usage()
	{
		$disktotal = disk_total_space ('/');
		$diskfree  = disk_free_space  ('/');
		$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';
		return $diskuse;
	}

	/*
		Get server uptime
		This function returns the server uptime:
	*/
	public static function server_uptime()
	{
		if(PHP_OS == "Linux")
		{
			$uptime = @file_get_contents( "/proc/uptime");
			if ($uptime !== false)
			{
				$uptime = explode(" ",$uptime);
				$uptime = $uptime[0];
				$days = explode(".",(($uptime % 31556926) / 86400));
				$hours = explode(".",((($uptime % 31556926) % 86400) / 3600));
				$minutes = explode(".",(((($uptime % 31556926) % 86400) % 3600) / 60));

				$time = ".";
				if ($minutes > 0)
					$time=$minutes[0]." minutos".$time;
				if ($minutes > 0 && ($hours > 0 || $days > 0))
					$time = ", ".$time;
				if ($hours > 0)
					$time = $hours[0]." horas".$time;
				if ($hours > 0 && $days > 0)
					$time = ", ".$time;
				if ($days > 0)
					$time = $days[0]." días".$time;
			}
			else
			{
				$time = false;
			}
		}
		else
		{
			$time = false;
		}
		return $time;
	}

	/*
		Get the kernel version
		This function returns the kernel version:
	*/
	public function shapeSpace_kernel_version()
	{
		$kernel = explode(' ', file_get_contents('/proc/version'));
		$kernel = $kernel[2];

		return $kernel;
	}

	/*
		Get the number of processes
		This function returns the number of running processes:
	*/
	public function shapeSpace_number_processes()
	{
		$proc_count = 0;
		$dh = opendir('/proc');

		while ($dir = readdir($dh))
		{
			if (is_dir('/proc/' . $dir))
			{
				if (preg_match('/^[0-9]+$/', $dir))
				{
					$proc_count ++;
				}
			}
		}
		return $proc_count;
	}

	/*
		Get current memory usage
		This function returns the current memory usage:
	*/
	public function shapeSpace_memory_usage()
	{
		$mem = memory_get_usage(true);
		if ($mem < 1024)
		{
			$$memory = $mem .' B';
		}
		elseif($mem < 1048576)
		{
			$memory = round($mem / 1024, 2) .' KB';
		}
		else
		{
			$memory = round($mem / 1048576, 2) .' MB';
		}
		return $memory;
	}

	public static function getSize($file)
	{
		/* primero verificamos si existe el archivo */
		$size=0;
		if(file_exists($file))
		{
			if(is_readable($file))
			{
				$size = filesize($file);
			}
		}
		return $size;
	}
}
?>
