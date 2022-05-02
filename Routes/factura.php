<?php
require_once '/Controllers/FacturaController.php';
/**
 * Ruta de prueba
 */
$_factura = new FacturaController;

if ($_SERVER['METHOD_REQUEST'] === 'GET') {
	$res = $_factura->get();

	//Enviamos los resultados...
	header('Content-Type: application/json');
	if (isset($res["result"])) {
		$statusCode = $res['status_code'];
		http_response_code(($statusCode));
	}
	echo json_encode($res);
 }