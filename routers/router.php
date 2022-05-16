<?php

/**
 * Definiremos las rutas acá.
 */

use api\controllers\ChargeController;
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
$router->get("/recibos/:receipt", function ($params) {
	$receipt = new ReceiptController;
	$receipt->retrieve($params);
});

$router->get("/recibos", function ($params) {
	$receipt = new ReceiptController;
	$receipt->get($params);
});
/**
 *  Receipts Endpoints
 */


 /**
 *  Charges Endpoints
 */

$router->get("/cobros/:charge", function ($params) {
	$charge = new ChargeController;
	$charge->retrieve($params);
});

$router->get("/cobros", function ($params) {
	$charge = new ChargeController;
	$charge->get($params);
});

$router->post("/cobros", function ($params) {
	$charge = new ChargeController;
	$charge->create($params);
});

/**
 *  Charges Endpoints
 */
