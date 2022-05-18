<?php
    class LogController implements Controller
    {
        public static function index($params)
        {
            $archivo = APP_ROOT_PATH."/gps/registro.txt";
            $file = fopen($archivo,"a+");
            foreach($_REQUEST as $req => $key)
            {
                fwrite($file,"Key: ".$req." :: ".$key."\n");
            }
            fclose($file);
        }
    }
?>