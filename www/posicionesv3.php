<?php
include "header.php";

$html="";
$script="";
if(isset($_GET["desde"])){
	$desde=$_GET["desde"];
}else{
	$desde=date("Y-m-d 00:00:00");
}
if(isset($_GET["hasta"])){
	$hasta=$_GET["hasta"];
}else{
	$hasta=date("Y-m-d H:i:s");
}

if(isset($_GET["id_virloc"])){
	if(isset($_GET["route"])){
		$titulo="Route between ".$desde." and ".$hasta;
		$mapa = XVM::getMapLastPositions($_GET["id_virloc"],$desde,$hasta);
		$script=$mapa[0];
		$html=$mapa[1];
	}else{
		$titulo="Last Position";
		$mapa = XVM::getMapLastPosition($_GET["id_virloc"]);
		$script=$mapa[0];
		$html=$mapa[1];
	}	
}else{
	$rs=XVM::getLastCarsPositions();
}
?>

<html>
<head>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function inicio() {
    <?php echo $script;?>
}
</script>
</head>
<body onload="inicio();">
<h2><?php echo $titulo;?></h2>
<?php
    echo $html;
?>
</body>
</html>