<?php 
include "db.php";

# HelloServer.php
# Copyright (c) 2005 by Dr. Herong Yang


function getEquipos(){
	$db=new db();
	$datos=$db->sqlQuery("select * from equipos");
	$retorna="";
	$retorna.="<table id='equipos'><caption>Cars</caption>";
	$retorna.="<tr><th>ID</th><th>Time</th><th>IP</th><th>Port</th><th>Position</th><th>Speed</th><th>Heading</th><th>Driver</th><th>Status</th></tr>";
	while($a = mysql_fetch_object($datos)){
		$retorna.="<tr><td>".$a->id."</td><td>".$a->timestamp."</td><td>".$a->ip."</td><td>".$a->puerto."</td><td>".$a->latitud.",".$a->longitud."</td><td>".$a->velocidad." Kms/h</td><td>".$a->rumbo."&deg;</td><td>".$a->chofer."</td><td>".$a->estado."</td>";
	}
	$retorna.="</table>";
	unset($db);
	return $retorna;
}

function getCrudo(){
	$db=new db();
	/* Crude information about the data on the database.
	 * $fecha - datetimeformat of the time
	 */
	$db=new db();
	$datos=$db->sqlQuery("select * from crudo order by fecha desc limit 20");
	$retorna="<table id='crudo'><caption>Feed</caption>";
	while($a = mysql_fetch_object($datos)){
		if($a->levantado==0){
			$retorna.="<tr class='crudo no-levantado'><td>".$a->fecha."</td><td>".$a->info."</td></tr>";
		}else{
			$retorna.="<tr class='crudo levantado'><td>".$a->fecha."</td><td>".$a->info."</td></tr>";
		}
	}
	$retorna.="</table>";
	return $retorna;
}


// $server = new SoapServer(null, array('uri' => "urn://www.herong.home/ree"));
// $server->addFunction("getAutos"); 
// $server->handle();
?>
