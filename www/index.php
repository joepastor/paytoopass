<?php 
include "header.php";
include "server.php";
?>
<html>
<head>
<title><?php echo date("H:i:s");?></title>
</head>
<meta http-equiv="refresh" content="10">
<div id="header">
<h1><img alt="PaytooPass" src="img/logo.png" width="60px">PAYTOO Pass - Testing Console</h1>
</div>
<div id="openModal" class="modalDialog">
	<div>
	<iframe src="mensaje.php"></iframe>
		<a href="#close" title="Close" class="close">X</a>
	</div>
</div>
<?php
echo getEquipos();
echo getPagos();
echo getCrudo();
?>
</html>
SMT12345670HOLA                                                                                          