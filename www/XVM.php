<?PHP
include "db.php";
class XVM {
    
    function __construct(){
        
    }

    function getGoogleMapsURL($latitud,$longitud){
        return "https://maps.google.com/maps?q=".$latitud.",".$longitud."&amp;num=1&amp;t=h&amp;ie=UTF8&amp;ll=".$latitud.",".$longitud."&amp;z=16&amp;output=embed";
    }

    function getLastPositions(){
        $recordSet=new db();
        $rs=$recordSet->sqlQuery("select id,timestamp,latitud,longitud,velocidad,rumbo from equipos;");
        return $rs;
    }

    function getLastPosition($id_virloc,$desde,$hasta,$cantidad=0){
        //Devuelve las ultimas coordenadas de de reporte de un virloc
        if($cantidad<>0){
            $strCantidad="limit ".$cantidad;
        }
        return DB::sqlQuery("select id_virloc,id,fecha,latitud,longitud,velocidad,rumbo from cocido where id_virloc=".$id_virloc." and fecha between '$desde' and '$hasta' and longitud <> 0.0 and latitud <> 0.0 order by fecha desc ".$strCantidad);
    }
    
    function getScriptGoogleMapsApi($id_virloc,$latitud,$longitud){
        $var = "";
        $var.="
        var myOptions = {
        center: new google.maps.LatLng(".$latitud.",".$longitud."),
        zoom: 17,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        var map".$id_virloc." = new google.maps.Map(document.getElementById('map".$id_virloc."'),myOptions);
        marker".$id_virloc." = new google.maps.Marker({
                                              position: new google.maps.LatLng(".$latitud.",".$longitud."),
                                              map: map".$id_virloc.",
                                              title: 'GodZZilla!!'
                                              });
        
        var ruta".$id_virloc." = [";
        
        $caca = XVM::getLastPosition($id_virloc,"2013-01-25 00:00","2013-01-25 23:59",0);
        while($gm=mysql_fetch_object($caca)){
            $var.="new google.maps.LatLng(".$gm->latitud.",".$gm->longitud."),\n";
        }
        $var.="];
        var lineas".$id_virloc." = new google.maps.Polyline({
                                                  path: ruta".$id_virloc.",
                                                  map: map".$id_virloc.",
                                                  strokeColor: '#222000',
                                                  strokeWeight: 4,
                                                  strokeOpacity: 0.6,
                                                  clickable: false
                                                  });
        ";
        return $var;
    }
}
?>
