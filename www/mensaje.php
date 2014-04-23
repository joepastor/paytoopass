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
	$db->sendMsg($_POST["virloc"],$_POST["mensaje"]);
}
?>
<body>
<form action="" method="post">
Virloc: 
<select id="virloc" name="virloc">
<?php 
$datos = $db->sqlQuery ( "select id from equipos" );
while ( $a = mysql_fetch_object ( $datos ) ) {
	echo "<option value=".$a->id.">".$a->id."</option>";
}	
?>
</select>
<br>
<textarea rows="3" cols="10" id="mensaje" name="mensaje"></textarea>
<button>Enviar</button>
</form>
</body>
</html>
