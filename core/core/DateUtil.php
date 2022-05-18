<?php
#===================================================#
#     coded by: Moises Espindola         _    _    #
#     nick: zaer00t                     | |  (_)   #
#    ___  _ __   ___   __ _  ___   __ _ | |_  _    #
#   / __|| '__| / _ \ / _` |/ __| / _` || __|| |   #
#  | (__ | |   |  __/| (_| |\__ \| (_| || |_ | |   #
#   \___||_|    \___| \__,_||___/ \__,_| \__||_|   #
#                                                  #
#    e-mail: zaer00t@gmail.com                     #
#    www: http://creasati.com.mx                   #
#    date: 12/Septiembre/2012                      #
#    code name: creasati.com.mx                    #
#==================================================#
class DateUtil {

    public static function now_filestamp() {
        return date("Ymd_His");
    }

    public static function compare_dates($date1, $date2) {
        $d1 = strtotime($date1);
        $d2 = strtotime($date2);

        if ($d1 == $d2) {
            return 0;
        }

        if ($d1 > $d2) {
            return 1;
        }

        if ($d1 < $d2) {
            return -1;
        }
    }

    public static function now() {
        return date("Y-m-d H:i:s");
    }

    static function to_sql_date($str) {
        $date = substr($str, 6, 4) . "-" . substr($str, 3, 2) . "-" . substr($str, 0, 2);
        return $date;
    }

    static function to_display_date($str) {
        $date = substr($str, 8, 2) . "/" . substr($str, 5, 2) . "/" . substr($str, 0, 4);
        return $date;
    }
}

?>
