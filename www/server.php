<?php
include_once "db.php";

// HelloServer.php
// Copyright (c) 2005 by Dr. Herong Yang
function getMensajes() {
	$db = new db ();
	/*
	 * Crude information about the data on the database. $fecha - datetimeformat of the time
	*/
	$db = new db ();
	$datos = $db->sqlQuery ( "select * from mensajes order by timestamp desc limit 20" );
	$retorna = "<table id='mensajes' class='report_table'><caption>Messages</caption>";
	$retorna .= "<tr><th>Date</th><th>ID</th><th>Message ID</th><th>Message</th><th>Sent</th><th>Delivered</th><th>Received</th><th>Read</th></tr>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		if ($a->recibido == 0) {
			$retorna .= "<tr class='bad_row'>";
		} else {
			$retorna .= "<tr class='ok_row'>";
		}
		$retorna.="<td>".$a->timestamp."</td>";
		$retorna.="<td>".$a->equipos_id."</td>";
		$retorna.="<td>".$a->id_mensaje."</td>";
		$retorna.="<td>".$a->mensaje."</td>";
		$retorna.="<td>".$a->enviado."</td>";
		$retorna.="<td>".$a->entregado."</td>";
		$retorna.="<td>".$a->recibido."</td>";
		$retorna.="<td>".$a->leido."</td>";
		$retorna.="</tr>";
	}
	$retorna .= "</table>";
	return $retorna;
}
function getEquipos() {
	$db = new db ();
	$datos = $db->sqlQuery ( "select * from equipos" );
	$retorna = "";
	$retorna .= "<table class='report_table'><caption>Cars</caption>";
	$retorna .= "<tr><th>ID</th><th>Last Report</th><th>Host:Port</th><th>Position</th><th>Driver</th><th>Status</th><th>Power</th><th>Msg</th></tr>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		$img_bat="battery_low.png";
		if($a->energia_ext>300){
			$img_bat="battery_mid.png";
			if($a->energia_ext>400){
				$img_bat="battery_full.png";
			}
		}
		if($a->energia_ext !=NULL && $a->energia_ext > 0){
			$img_bat="battery_charging.png";
		}
		
		$retorna .= "<tr>
						<td>" . $a->id . "</td><td>" . $a->timestamp . "</td>
						<td>" . $a->ip . ":" . $a->puerto . "</td>
						<td>
							<a href='posicionesv3.php?id_virloc=" . $a->id . "'>
								<img src='img/position-google-maps.png' title='Last Position' width='32px'>
							</a>
							<a href='posicionesv3.php?id_virloc=" . $a->id . "&route=1'><img src='img/route.png' width='32px' title='Route'></a>
							" . $a->velocidad . " Kms/h <img style='transform: -moz-transform: rotate(" . $a->rumbo . "deg);
   								-webkit-transform: rotate(" . $a->rumbo . "deg);' title='Heading' src='img/flecha.png' width='16px'>" . $a->rumbo . "&deg;</td>
						<td>" . $a->chofer . "</td>
						<td>" . $a->estado . "</td>
						<td><img src='img/".$img_bat."' width='48px'>" . $energia_ext . "</td>
						<td><a href='mensaje.php?virloc=$a->id' target='_blank'><img src='img/mail.png' width='32px'></a></td>
					</tr>";
	}
	$retorna .= "</table>";
	unset ( $db );
	return $retorna;
}

function getCrudo() {
	$db = new db ();
	/*
	 * Crude information about the data on the database. $fecha - datetimeformat of the time
	 */
	$db = new db ();
	$datos = $db->sqlQuery ( "select * from crudo order by fecha desc limit 20" );
	$retorna = "<table id='crudo' class='report_table'><caption>Feed</caption>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		if ($a->levantado == 0) {
			$retorna .= "<tr class='bad_row'><td>" . $a->fecha . "</td><td>" . $a->info . "</td></tr>";
		} else {
			$retorna .= "<tr class='ok_row'><td>" . $a->fecha . "</td><td>" . $a->info . "</td></tr>";
		}
	}
	$retorna .= "</table>";
	return $retorna;
}

function getPagos() {
	$db = new db ();
	/*
	 * Crude information about the data on the database. $fecha - datetimeformat of the time
	 */
	$db = new db ();
	$datos = $db->sqlQuery ( "select fecha,cuenta,id_chofer,monto,equipos_id,tipo_cobro,tiempo,distancia, estado,mensaje from pagos order by fecha desc limit 10" );
	$retorna = "<table class='report_table'><caption>Payments</caption>";
	$retorna .= "<tr><th>Time</th><th>Driver</th><th>Amount</th><th>ID</th><th>Method</th><th>Time</th><th>Distance</th><th>Status</th><th>Msg</th></tr>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		switch ($a->estado){
			case "OK":
				$class = "ok_row";
				break;
			case "PENDING";
				$class = "pending_row";
				break;
			case "TOSIGN";
				$class = "tosign_row";
				break;				
			default:
				$class = "bad_row";
		}
		$retorna .= "<tr class='$class'>";
		$retorna .= "<td>" . $a->fecha . "</td>";
		//$retorna .= "<td>" . $a->cuenta . "</td>";
		$retorna .= "<td>" . $a->id_chofer . "</td>";
		$retorna .= "<td>" . $a->monto . "</td>";
		$retorna .= "<td>" . $a->equipos_id . "</td>";
		$retorna .= "<td>" . $a->tipo_cobro . "</td>";
		$retorna .= "<td>" . $a->tiempo . "</td>";
		$retorna .= "<td>" . $a->distancia . "</td>";
		$retorna .= "<td>" . $a->estado . "</td>";
		$retorna .= "<td>" . $a->mensaje . "</td>";
		$retorna .= "</tr>";
	}
	$retorna .= "</table>";
	return $retorna;
}
?>