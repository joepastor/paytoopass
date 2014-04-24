<?php
include "db.php";

// HelloServer.php
// Copyright (c) 2005 by Dr. Herong Yang
function getEquipos() {
	$db = new db ();
	$datos = $db->sqlQuery ( "select * from equipos" );
	$retorna = "";
	$retorna .= "<table class='report_table'><caption>Cars</caption>";
	$retorna .= "<tr><th>ID</th><th>Time</th><th>IP</th><th>Port</th><th>Position</th><th>Speed</th><th>Heading</th><th>Driver</th><th>Status</th></tr>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		$retorna .= "<tr><td>" . $a->id . "</td><td>" . $a->timestamp . "</td><td>" . $a->ip . "</td><td>" . $a->puerto . "</td><td>" . $a->latitud . "," . $a->longitud . "</td><td>" . $a->velocidad . " Kms/h</td><td>" . $a->rumbo . "&deg;</td><td>" . $a->chofer . "</td><td>" . $a->estado . "</td>";
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
	$retorna = "<table id='crudo'><caption>Feed</caption>";
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
	$datos = $db->sqlQuery ( "select fecha,cuenta,id_chofer,monto,id_virloc,tipo_cobro,tiempo,distancia, estado,mensaje from pagos order by fecha desc limit 10" );
	$retorna = "<table class='report_table'><caption>Pagos</caption>";
	$retorna .= "<tr><th>Time</th><th>Driver</th><th>Amount</th><th>ID</th><th>Method</th><th>Time</th><th>Distance</th><th>Status</th><th>Msg</th></tr>";
	while ( $a = mysql_fetch_object ( $datos ) ) {
		if ($a->estado == "OK") {
			$class = "ok_row";
		} else {
			$class = "bad_row";
		}
		$retorna .= "<tr class='$class'>";
		$retorna .= "<td>" . $a->fecha . "</td>";
		//$retorna .= "<td>" . $a->cuenta . "</td>";
		$retorna .= "<td>" . $a->id_chofer . "</td>";
		$retorna .= "<td>" . $a->monto . "</td>";
		$retorna .= "<td>" . $a->id_virloc . "</td>";
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