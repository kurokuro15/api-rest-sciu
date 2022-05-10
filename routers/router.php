<?php
/**
 * Definiremos las rutas acá.
 */
use \api\Models\Student;
use \api\Controllers\StudentController;
$router->get("/estudiantes", function ($params) {
	$student = new Student;
	$data = $student->getAll();
	$response = $GLOBALS['response'];
	$response->send($data);
});

$router->get("/estudiantes/:cedula", function($params) {
	$student = new StudentController;
	$student->retrieve($params);
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
