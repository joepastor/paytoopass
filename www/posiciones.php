<?php
include "XVM.php";
$html="";
$script="";
    $rs=XVM::getLastPositions();
    while($pos=mysql_fetch_object($rs)){

        $script.=XVM::getScriptGoogleMapsApi($pos->id,$pos->latitud,$pos->longitud);
        $url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
        ?>
        <div style="width: 425px; height: 375px; border: 1px solid;float: left;padding: 3px;text-align: center;">
            <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src='<?php echo $url;?>'></iframe>
            <a href="<?php echo $url;?>"><?php echo $pos->fecha;?> Vehiculo <?php echo $pos->id." a ".$pos->velocidad;?> kms/h Rumbo <?php echo $pos->rumbo;?></a>
        </div>
        <?php
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
