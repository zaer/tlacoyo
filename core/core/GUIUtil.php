<?php
#===================================================#
#    coded by: Moises Espindola      _  _   #
#    nick: zaer00t                   | |  (_)   #
#   ___  _ __   ___   __ _  ___   __ _ | |_  _  #
#   / __|| '__| / _ \ / _` |/ __| / _` || __|| |   #
#  | (__ | |   |  __/| (_| |\__ \| (_| || |_ | |   #
#   \___||_|    \___| \__,_||___/ \__,_| \__||_|   #
#                                                 #
#   e-mail: zaer00t@gmail.com                    #
#   www: http://creasati.com.mx                #
#   date: 12/Septiembre/2012                      #
#   code name: creasati.com.mx                  #
#==================================================#
class GUIUtil {

    public static function error_msg($tipo, $titulo, $msg, $detalle=0,$redirect = "javascript:window.history.back()")
    {
        ?>
        <link href="<?=APP_ASSETS?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?=APP_ASSETS?>/css/style.css" rel='stylesheet' type='text/css' />
        <style type="text/css">
        blockquote{
            border-left:none
        }

        .quote-badge{
            background-color: rgba(234, 155, 0, 1);
        }

        .quote-box{

            overflow: hidden;
            border-radius: 2px;
            background-color: #eee;
            color:#333;
            width: 80%;
            padding:50px;
            box-shadow: 1px 1px 1px 1px #999;
        }

        .quotation-mark{

            margin-top: 10px;
            font-weight: bold;
            font-size:18px;
            color:crimson;
            font-family: Courier, Georgia, Serif;
        }

        .quote-text{

            font-size: 12px;
            margin-top: 5px;
        }
        </style>
        <div class="container" style="margin: 3%;">
            <blockquote class="quote-box">
                <p class="quotation-mark">
                    <?=$titulo?>
                </p>
                <p class="quote-text">
                    <?php
                        echo "<b>FILE:</b> ".$msg->getFile()."<br>";
                        echo "<b>LINE:</b> ".$msg->getLine()."<br>";
                        if($detalle!=0)
                            Util::debug($msg,"MENSAJE: ");
                    ?>
                </p>
            </blockquote>
        </div>
        <?php
        #exit();
        #return 0;
    }
}

?>
