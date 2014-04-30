<?php
include "XVM.php";
$html="";
$script="";
$hasta=date("Y-m-d H:i:s");
$desde=date("Y-m-d 00:00:00");

if(isset($_GET["id_virloc"])){
	if(isset($_GET["route"])){
		echo "RUTA";
		$mapa = XVM::getMapLastPositions($_GET["id_virloc"],$desde,$hasta);
		$script=$mapa[0];
		$html=$mapa[1];

// 		$rs=XVM::getLastPositions($_GET["id_virloc"],"2013-01-01 00:00","2015-01-01 00:00");
// 		while($pos=mysql_fetch_object($rs)){
// 			die();
// 			$script.=XVM::getScriptGoogleMapsApi($pos->id,$pos->latitud,$pos->longitud);
// 			$url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
// 			$html.="<div id=map".$pos->id." style='width:400px; height:400px'></div>";
// 		}
	}else{
		echo "ULTIMA POSICION";
		$mapa = XVM::getMapLastPosition($_GET["id_virloc"]);
		$script=$mapa[0];
		$html=$mapa[1];
// 		$rs=XVM::getLastPosition($_GET["id_virloc"]);
// 		while($pos=mysql_fetch_object($rs)){
// 			$script.=XVM::getMapPosition($pos->id,$pos->latitud,$pos->longitud);
// 			//$url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
// 			$html.="<div id=map".$pos->id." style='width:400px; height:400px'></div>";
// 		}
	}
		
}else{
	$rs=XVM::getLastCarsPositions();
}

// while($pos=mysql_fetch_object($rs)){
// 	break;
// 	echo "error";
// 	$script.=XVM::getScriptGoogleMapsApi($pos->id,$pos->latitud,$pos->longitud);
// 	//$url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
// 	$html.="<div id=map".$pos->id." style='width:400px; height:400px'></div>";
// }
?>

<html>
<head>
<link type="css/text" rel="stylesheet" href="css/virloc.css">
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
function inicio() {
    <?php echo $script;?>
}
</script>
</head>
<body onload="inicio();">
<hr>
<?PHP
    echo $html;
?>
</body>

</html>
