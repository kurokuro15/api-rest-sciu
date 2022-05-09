<?php

/**
 * Definiremos las rutas acá.
 */

use \api\controllers\FacturaController;

$router->get("/estudiantes", function ($params) {

});

$router->get("/estudiantes/:cedula", function ($params) {
	$factura = new FacturaController;
	try {
		$body = $factura->get($params);
		$response = $GLOBALS['response'];
		if ($body) {
			$response->send($body,200);
		}
	} catch (ValueError $err) {
		$response->send("{\"error\":\"{$err->messange}\"}",404);
	}
});

$router->get("/ordenes", function ($params) {
	print_r('Hola desde la ruta Ordenes');
	if (!empty($params))
		print_r('Hola desde la ruta Ordenes con el estudiante: <br>');
	print_r('Hola desde la ruta Ordenes con el recibo: <br>');
});
$router->get("/ordenes/:id_registro", function ($params) {
	if (!empty($params))
		print_r($params['id_registro']);
});

$router->get("/recibos", function ($params) {
	print_r('Hola desde la ruta Recibos');
	if (!empty($params))
		print_r('Hola desde la ruta recibos con el estudiante: <br>');
	print_r('Hola desde la ruta recibos con la orden: <br>');
});

$router->get("/recibos/:id", function ($params) {
	if (!empty($params))
		print_r($params['id']);
});
