<?php
include "XVM.php";
$html="";
$script="";
    $rs=XVM::getLastPositions();
    while($pos=mysql_fetch_object($rs)){
        $script.=XVM::getScriptGoogleMapsApi($pos->id,$pos->latitud,$pos->longitud);
        $url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
        $html.="<div id=map".$pos->id." style='width:400px; height:400px'></div>";
    }
?>

<html>
<head>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
function inicio() {
    <?php echo $script;?>
}
</script>
</head>
<body onload="inicio();">
<hr>
<?PHP
    echo $html;
?>
</body>

</html>
