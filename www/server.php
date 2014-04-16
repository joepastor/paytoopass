<?php 
include "db.php";

# HelloServer.php
# Copyright (c) 2005 by Dr. Herong Yang
#

function getAutos(){
	$db=new db();
	//$rs=$db->sqlQuery("select * from equipos where timestamp > date_sub(now(), interval 1 hour)");
	$rs=$db->sqlQuery("select * from equipos");
	$r=array();
	$posicion=0;
	while($a = mysql_fetch_object($rs)){
		$r[$posicion]=$a;
		$posicion++;
		//$r+=$a;
	}
	return $r;
}

function getCrudo(){
	/* Crude information about the data on the database.
	 * $fecha - datetimeformat of the time
	 */
	$datos=$db->sqlQuery("select fecha,info from crudo order by fecha desc limit 30");
	while($a = mysql_fetch_object($datos)){
		echo $a->fecha . " : " . $a->info."<br>";
	
	}	
}




$server = new SoapServer(null, array('uri' => "urn://www.herong.home/ree"));
$server->addFunction("getAutos"); 
$server->handle();
?>
