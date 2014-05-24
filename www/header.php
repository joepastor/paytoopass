 <?php
 include_once "server.php";
 include_once "XVM.php";
 ?>
 <html>
<head>
<title>PaytooPass - TEST</title>
<meta http-equiv="refresh" content="1">
</head>
 <!-- Bootstrap -->
 <link href="css/bootstrap.min.css" rel="stylesheet">
 <link href="css/virloc.css" rel="stylesheet">
<div id="header" onclick="window.location='index.php'">
	<h1><img alt="PaytooPass" src="img/logo.png" width="60px">PAYTOO Pass - Testing Console</h1>
	<div id="hora"><?php echo date("H:i:s");?></div>
</div>