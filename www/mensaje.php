<?php 

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
	$mensaje=$_POST["mensaje"];
	if($_POST["tipo"]=="vircom" || $_POST["tipo"]=="pantalla"){
		$vircom=1;
		if($_POST["tipo"]=="pantalla"){
			$largo=strlen($_POST["mensaje"]);
			$mensaje="SMT12345670".str_pad($_POST["mensaje"],94);
			$mensaje=strtoupper($mensaje);
		}
	}
	
	$db->sendMsg($_POST["virloc"],$mensaje,$vircom);
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
<input type="radio" name="tipo" value="pantalla" checked>Pantalla</input>
<input type="radio" name="tipo" value="vircom">Vircom</input>
<input type="radio" name="tipo" value="virloc">Virloc</input>
<br>
<textarea rows="3" cols="45" id="mensaje" maxlength="94" name="mensaje"></textarea>
<br>
<button>Enviar</button>
</form>
</body>
</html>
