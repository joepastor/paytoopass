<?php 
include "header.php";
include "server.php";
$db = new db ();
?>
<html>
<head>
<title><?php echo date("H:i:s");?></title>
</head>
<?php
if(isset($_POST["virloc"]) && isset($_POST["mensaje"]) && $_POST["mensaje"]!=""){
	$vircom=0;
	if($_POST["tipo"]=="pantalla"){
		$vircom=1;
	}

	$db->sendMsg($_POST["virloc"],$_POST["mensaje"],$vircom);
}
?>
<body>
<form action="" method="post">
Virloc: 
<select id="virloc" name="virloc">
<?php 
$datos = $db->sqlQuery ( "select id from equipos" );
while ( $a = mysql_fetch_object ( $datos ) ) {
	$selected = ($_GET["virloc"] == $a->id) ? " selected" : "";
	echo "<option value=".$a->id.$selected.">".$a->id."</option>";
}
?>
</select>
<br>
<input type="radio" name="tipo" value="pantalla" checked>Screen</input>
<input type="radio" name="tipo" value="interno">Core</input>
<br>
<textarea rows="3" cols="20" id="mensaje" name="mensaje"></textarea>
<br>
<button>Enviar</button>
</form>
</body>
</html>
