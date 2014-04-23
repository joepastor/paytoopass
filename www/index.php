<?php 
include "header.php";
include "server.php";
?>
<html>
<head>
<title><?php echo date("H:i:s");?></title>
</head>
<meta http-equiv="refresh" content="1">
<?php
echo getEquipos();
echo getCrudo();
echo getPagos();
?>
</html>