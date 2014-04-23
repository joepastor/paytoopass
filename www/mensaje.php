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
	if($_POST["TIPO"]=="pantalla"){
		$mensaje="SSC26".$_POST["mensaje"];
		$id=$_POST["virloc"]."V";
	}else{
		$mensaje=$_POST["mensaje"];
		$id=$_POST["virloc"];
	}
	if($mensaje){
		$db->sendMsg($id,$mensaje);
	}
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
<input type="radio" name="tipo" value="pantalla" checked>Screen</input>
<input type="radio" name="tipo" value="interno">Core</input>
<br>
<textarea rows="3" cols="10" id="mensaje" name="mensaje"></textarea>
<br>
<button>Enviar</button>
</form>
</body>
</html>
