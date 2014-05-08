<?php include_once "header.php";
include "model/drivers.php";
?>
<div id="openModal" class="modalDialog">
	<div>
		<iframe src="mensaje.php"></iframe>
		<a href="#close" title="Close" class="close">X</a>
	</div>
</div>
<?php
$caca=new driver();
$caca->getChofer(7777);
var_dump($caca);
echo getEquipos();
echo getPagos();
echo getCrudo();
?>
</html>                                                                                          