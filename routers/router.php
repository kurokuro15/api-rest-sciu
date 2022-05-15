<?php

/**
 * Definiremos las rutas acá.
 */

use \api\Controllers\StudentController;
use \api\Controllers\OrderController;
use api\controllers\ReceiptController;

/**
 *  Students Endpoints
 */
$router->get("/estudiantes", function ($params) {
	$student = new StudentController;
	$student->get($params);
});

$router->get("/estudiantes/:cedula", function ($params) {
	$student = new StudentController;
	$student->retrieve($params);
});
/**
 *  Students Endpoints
 */


/**
 *  Orders Endpoints
 */
$router->get("/ordenes", function ($params) {
	$order = new OrderController;
	$order->get($params);
});

$router->get("/ordenes/:order", function ($params) {
	$order = new OrderController;
	$order->retrieve($params);
});

// POST Method
$router->post("/ordenes", function($params){
	$order = new OrderController;
	$order->create($params);
});

/**
 *  Orders Endpoints
 */

 
/**
 *  Receipts Endpoints
 */
$router->get("/recibos", function ($params) {
	$receipt = new ReceiptController;
	$receipt->get($params);
});
/**
 *  Receipts Endpoints
 */
