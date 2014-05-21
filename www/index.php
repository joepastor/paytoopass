<?php include_once "header.php";
?>
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