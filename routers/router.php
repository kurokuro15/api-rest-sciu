<?php
/**
 * Definiremos las rutas acá.
 */
use \api\Controllers\StudentController;
use \api\Controllers\OrderController;
use api\controllers\ReceiptController;

$router->get("/estudiantes", function ($params) {
	$student = new StudentController;
	$student->get($params);
});

$router->get("/estudiantes/:cedula", function($params) {
	$student = new StudentController;
	$student->retrieve($params);
});

$router->get("/ordenes", function ($params) {
	$order = new OrderController;
	$order->get($params);
});

$router->get("/ordenes/:order", function ($params) {
	$order = new OrderController;
	$order->retrieve($params);
});

$router->get("/recibos", function ($params) {
	$receipt = new ReceiptController;
	$receipt->get($params);
});

