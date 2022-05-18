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
	class Build
	{
        public static function scaffolding($tabla)
        {
            $link = mysql_connect("localhost","root","nano");
            if(!mysql_select_db('autos'))
            {
                throw new AppException("Ocurrio un error en el modulo SCAFOLDING");
            }
            $sql = "show full columns from {$tabla}";
            $r1 = mysql_query($sql);

            while($row=mysql_fetch_array($r1))
            {
                echo "\$datos['{$row['Field']}']=\${$row['Field']};<br>";
            }
        }
        public static function buildGetSet($tabla)
        {
            $link = mysql_connect("localhost","root","nano");
            if(!mysql_select_db('autos'))
            {
                throw new AppException("Ocurrio un error en el modulo SCAFOLDING GetSet");
            }
            $sql = "show full columns from {$tabla}";
            $r1 = mysql_query($sql);

            // construimos los GET
            while($row=mysql_fetch_array($r1))
            {
                echo "public function get_".($row['Field'])."() { return \$this->{$row['Field']}; }<br>";
            }
            $r1 = mysql_query($sql);
            // construimos los SET
            echo "<br># SETS<br><br>";
            while($row=mysql_fetch_array($r1))
            {
                echo "public function set_".($row['Field'])."(\${$row['Field']}) { \$this->{$row['Field']}=\${$row['Field']}; }<br>";
            }

        }

        public static function buildUpdate($tabla)
        {
            $link = mysql_connect("localhost","root","nano");
            if(!mysql_select_db('autos'))
            {
                throw new AppException("Ocurrio un error en el modulo SCAFOLDING GetSet");
            }
            $sql = "show full columns from {$tabla}";
            $r1 = mysql_query($sql);

            // construimos los GET
            $campos=array();
            while($row=mysql_fetch_array($r1))
            {
                $campos[] = $row['Field'];
            }

            $build="private function update(";
            $c2='';
            $codigo='';

            for($i=0;$i<count($campos);$i++)
            {
                $c2 .= $campos[$i];
                if($i<count($campos)-1)
                $c2.=",";
                $codigo .= "'{$campos[$i]}'=>\$this->{$campos[$i]},
                <br>";
            }

            echo $build.")<br>{\$datos = array(<br>".$codigo.");<br> \$this->db->update('{$tabla}', \$datos, 'id=?', array((int)\$this->id));}<br>";
        }
		public static function buildLeer($tabla)
		{
            $link = mysql_connect("localhost","root","nano");
            if(!mysql_select_db('autos'))
            {
                throw new AppException("Ocurrio un error en el modulo SCAFOLDING GetSet");
            }
            $sql = "show full columns from {$tabla}";
            $r1 = mysql_query($sql);

            $campos=array();
            while($row=mysql_fetch_array($r1))
            {
                $campos[] = $row['Field'];
            }

            $build="public function leer(\$id";
            $c2='';
            $codigo='';

            for($i=0;$i<count($campos);$i++)
            {
                $c2 .= $campos[$i];
                if($i<count($campos)-1)
                $c2.=",";
                $codigo .= "'{$campos[$i]}'=>\$this->{$campos[$i]},
                <br>";
            }

            echo $build.")<br>{\$datos = array(<br>".$codigo.");<br> \$this->db->update('{$tabla}', \$datos, 'id=?', array((int)\$this->id));}<br>";
		}
	}
?>