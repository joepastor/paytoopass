<?php 
include "header.php";
?>

<html>
<meta http-equiv="refresh" content="1">
<?php
echo "<h1>Hora actual: ".date("Y-m-d H:i")."</h1>";
//include "db.php";
include "server.php";
# HelloClient.php
# Copyright (c) 2005 by Dr. Herong Yang
#

	$db = new DB();

	$datos=$db->sqlQuery("select * from equipos");
	?>
	<table width="100%" border=1>
	<?php
	while($a = mysql_fetch_object($datos)){
		echo "<tr><td>".$a->id."</td><td>".$a->timestamp."</td><td>".$a->ip."</td><td>".$a->puerto."</td><td>".$a->latitud.",".$a->longitud."</td><td>".$a->velocidad."</td><td>".$a->rumbo."</td><td>".$a->chofer."</td><td>".$a->estado."</td>";
	}
	?>
	</table>
	
	<?php
	$datos=$db->sqlQuery("select fecha,info from crudo order by fecha desc limit 30");
	while($a = mysql_fetch_object($datos)){
		echo $a->fecha . " : " . $a->info."<br>";
	}
?>
