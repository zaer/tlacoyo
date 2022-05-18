<?php

	class Permisos
	{
		private $db;
		private $result;

		public function __construct($db){
			$this->db=$db;
		}

		public function getPermiso($usuario_id = 0,$recurso_id=0)
		{
			$sql = "SELECT IF(SUM(consultar) >= 1, 1,0) as consultar, ".
					   "IF(SUM(agregar) >= 1, 1,0) as agregar, ".
					   "IF(SUM(editar) >= 1, 1,0) as editar, ".
					   "IF(SUM(eliminar) >= 1, 1,0) as eliminar ".
				"FROM perfil_recurso ".
				"WHERE id_recurso = ".(int)$recurso_id." ".
				"AND id_perfil IN ( ".
					"SELECT id_perfil ".
					"FROM usuario_perfil ".
					"WHERE id_usuario = ". (int)$usuario_id." ".
				") ".
				"GROUP BY id_recurso";
			#echo $sql;
			$r1 = $this->db->execute($sql);
			$this->result = $this->db->fArray($r1);
			$r1->close();
		}

		public function lectura()
		{
			if(!(count($this->result)==0))
			{
				$permiso = current($this->result);
				if($permiso['consultar']==1)
					return true;
				else
					return false;
			}
			return false;
		}

		public function editar()
		{
			if(!(count($this->result)==0))
			{
				$permiso = current($this->result);
				if($permiso['editar']==1)
					return true;
				else
					return false;
			}
			return false;
		}

		public function eliminar()
		{
			if(!(count($this->result)==0))
			{
				$permiso = current($this->result);
				if($permiso['eliminar']==1)
					return true;
				else
					return false;
			}
			return false;
		}

		public function agregar()
		{
			if(!(count($this->result)==0))
			{
				$permiso = current($this->result);
				if($permiso['agregar']==1)
					return true;
				else
					return false;
			}
			return false;
		}
	}
/*
select
	usr.nombre as 'usr_nom',
	perf.nombre as 'Perfil',
	rec.nombre as 'Modulo',
	usr.id as 'usr_id',
	usrp.id_perfil as 'id_perfil',
	rec.id as 'id_ecurso',
	perfr.consultar as 'c',
	perfr.agregar as 'a',
	perfr.editar as 'e',
	perfr.eliminar as 'd'
from
	usuario usr
inner join usuario_perfil usrp on usr.id = usrp.id_usuario
inner join perfil_recurso perfr on perfr.id_perfil = usrp.id_perfil
inner join recurso rec on perfr.id_recurso = rec.id
inner join perfil perf on perfr.id_perfil = perf.id
where
	usr.id = 169 and perfr.id_perfil = 5
order by usr.id;

/* sopota madre, consultas magicas */
/*
SELECT IF(SUM(consultar) >= 1, 1,0) as consultar,
IF(SUM(agregar) >= 1, 1,0) as agregar,
IF(SUM(editar) >= 1, 1,0) as editar,
IF(SUM(eliminar) >= 1, 1,0) as eliminar
FROM perfil_recurso
WHERE id_recurso = 1
AND id_perfil IN (
SELECT id_perfil
FROM usuario_perfil
WHERE id_usuario = 1
)GROUP BY id_recurso;
*/
?>
