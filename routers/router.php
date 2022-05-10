<?php
/**
 * Definiremos las rutas acá.
 */
use \api\Controllers\StudentController;

$router->get("/estudiantes", function ($params) {
	$student = new StudentController;
	$student->get($params);
});

$router->get("/estudiantes/:cedula", function($params) {
	$student = new StudentController;
	$student->retrieve($params);
});

