<?php
global $db;
class driver{
	var $id;
	var $nombre;
	var $apellido;
	var $dni;
	var $fecha_nacimiento;
	var $usuario;
	var $clave;
	var $mandatarias_id;
	
	function getChofer($usuario){
		echo $usuario;
		$d=new DB();
		$rs=$d->sqlQuery("select * from choferes where usuario=".$usuario);
		$chofer=mysql_fetch_object($rs);
		
		$this->id=$chofer->id;
		$this->apellido=$chofer->apellido;
		$this->apellido=$chofer->apellido;
		$this->dni=$chofer->dni;
		$this->fecha_nacimiento=$chofer->fecha_nacimiento;
		$this->usuario=$chofer->usuario;
		$this->clave=$chofer->clave;
		$this->mandatarias_id=$chofer->mandatarias_id;
		
	}
	function setChofer(){
		$sql="insert into choferes set
			apellido='".$this->apellido."',
			apellido='".$this->nombre."',
			dni='".$this->dni."',
			fecha_nacimiento='".$this->fecha_nacimiento."',
			usuario='".$this->usuario."',
			clave='".$this->clave."',
			mandatarias_id='".$this->mandatarias_id."'";
		echo $sql;
	}
	
}
?>