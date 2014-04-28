<?php 
include "header.php";
include "server.php";
?>
<html>
<head>
<title><?php echo date("H:i:s");?></title>
</head>
<!-- <meta http-equiv="refresh" content="1"> -->
<a href="#openModal">Open Modal</a>

<div id="openModal" class="modalDialog">
	<div>
	<iframe src="mensaje.php"></iframe>
		<a href="#close" title="Close" class="close">X</a>
	</div>
</div>
<?php
echo getEquipos();
echo getCrudo();
echo getPagos();
?>
</html>