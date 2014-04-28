<?PHP
include "db.php";
class XVM {
    
    function __construct(){
        
    }

    function getGoogleMapsURL($latitud,$longitud){
        return "https://maps.google.com/maps?q=".$latitud.",".$longitud."&amp;num=1&amp;t=h&amp;ie=UTF8&amp;ll=".$latitud.",".$longitud."&amp;z=16&amp;output=embed";
    }
    function getLastCarsPositions(){
        $recordSet=new db();
        $rs=$recordSet->sqlQuery("select id,timestamp,latitud,longitud,velocidad,rumbo from equipos");
        return $rs;
    }
    function getLastPosition($id_virloc){
    	$recordSet=new db();
    	$rs=$recordSet->sqlQuery("select id,timestamp,latitud,longitud,velocidad,rumbo from equipos where id=".$id_virloc);
    	return $rs;
    }
    function getLastPositions($id_virloc,$desde,$hasta){
    	$recordSet=new db();
    	$sql="select id_virloc,fecha,latitud,longitud,velocidad,rumbo from posiciones where id_virloc=".$id_virloc." and fecha between '".$desde."' and '".$hasta."' limit 100";
    	$rs=$recordSet->sqlQuery($sql);
    	return $rs;
    }
//     function getScriptGoogleMapsApi($id_virloc,$latitud,$longitud){
//         $var = "";
//         $var.="
//         var myOptions = {
//         center: new google.maps.LatLng(".$latitud.",".$longitud."),
//         zoom: 17,
//         mapTypeId: google.maps.MapTypeId.ROADMAP
//         };
        
//         var map".$id_virloc." = new google.maps.Map(document.getElementById('map".$id_virloc."'),myOptions);
//         marker".$id_virloc." = new google.maps.Marker({
//                                               position: new google.maps.LatLng(".$latitud.",".$longitud."),
//                                               map: map".$id_virloc.",
//                                               title: 'ID:".$id_virloc."'
//                                               });
        
//         var ruta".$id_virloc." = [";
        
//         $caca = XVM::getLastPositions($id_virloc,"2013-01-25 00:00","2015-01-25 23:59");
//         while($gm=mysql_fetch_object($caca)){
//             $var.="new google.maps.LatLng(".$gm->latitud.",".$gm->longitud."),\n";
//         }
//         $var.="];
//         var lineas".$id_virloc." = new google.maps.Polyline({
//                                                   path: ruta".$id_virloc.",
//                                                   map: map".$id_virloc.",
//                                                   strokeColor: '#222000',
//                                                   strokeWeight: 4,
//                                                   strokeOpacity: 0.6,
//                                                   clickable: false
//                                                   });
//         ";
//         return $var;
//     }
    function getMapPosition($id_virloc,$latitud,$longitud){
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
                                              title: 'ID:".$id_virloc."'
                                              });
		";
    	return $var;
    }
    function getMapLastPosition($id_virloc){
    	$rs=XVM::getLastPosition($_GET["id_virloc"]);
    	while($pos=mysql_fetch_object($rs)){
    		$script.=XVM::getMapPosition($pos->id,$pos->latitud,$pos->longitud);
    		//$url=XVM::getGoogleMapsURL($pos->latitud,$pos->longitud);
    		$html.="<div id=map".$pos->id." class='div_map'></div>";
    	}
    	return [$script,$html];
    }
    function getMapLastPositions($id_virloc,$desde,$hasta){
    	$rs = XVM::getLastPosition($id_virloc);
    	$gm=mysql_fetch_object($rs);
    	
    	$var = "";
    	$var.="
        var myOptions = {
        center: new google.maps.LatLng(".$gm->latitud.",".$gm->longitud."),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    
        var map".$id_virloc." = new google.maps.Map(document.getElementById('map".$id_virloc."'),myOptions);
        marker".$id_virloc." = new google.maps.Marker({
                                              position: new google.maps.LatLng(".$gm->latitud.",".$gm->longitud."),
                                              map: map".$id_virloc.",
                                              title: 'ID:".$id_virloc."'
                                              });
    
        var ruta".$id_virloc." = [";
    	$caca = XVM::getLastPositions($id_virloc,$desde,$hasta);
    	while($gm=mysql_fetch_object($caca)){
    		$var.="new google.maps.LatLng(".$gm->latitud.",".$gm->longitud."),\n";
    	}
    	$var.="];
    			
        var lineas".$id_virloc." = new google.maps.Polyline({
                                                  path: ruta".$id_virloc.",
                                                  map: map".$id_virloc.",
                                                  strokeColor: '#222000',
                                                  strokeWeight: 3,
                                                  strokeOpacity: 0.5,
                                                  clickable: true
                                                  });
        ";
    	$html="<div id=map".$id_virloc." class='div_map'></div>";
    	return [$var,$html];
    }
}
?>
