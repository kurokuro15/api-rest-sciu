<?php
namespace api\routers;
use \api\Controllers\FacturaController as FacturaController;
/**
 * Ruta de prueba a ver como hago que funcione....
 */

$_factura = new FacturaController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$res = $_factura->get($datos);
	//Enviamos los resultados...
	header('Content-Type: application/json');
	if (isset($res["result"])) {
		$statusCode = $res['status_code'];
		http_response_code(($statusCode));
		echo json_encode($res);
	}
}