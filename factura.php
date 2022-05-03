<?php
include 'Controllers/FacturaController.php';
include 'Models/Factura.php';
use \api\Controllers\FacturaController as FacturaController;
/**
 * Ruta de prueba
 */
$_factura = new FacturaController;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$res = $_factura->get();
	//Enviamos los resultados...
	header('Content-Type: application/json');
	if (isset($res["result"])) {
		$statusCode = $res['status_code'];
		http_response_code(($statusCode));
	}
	echo json_encode($res);
}