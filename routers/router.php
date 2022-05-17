<?php

/**
 * Definiremos las rutas acá.
 */

use api\Controllers\StudentController;
use api\controllers\ChargeController;
use api\Controllers\OrderController;
use api\controllers\ReceiptController;

/**
 *  Students Endpoints
 */
//get all
$router->get("/estudiantes", function ($params) {
	$student = new StudentController;
	$student->get($params);
});
//retrieve one
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
//get all
$router->get("/ordenes", function ($params) {
	$order = new OrderController;
	$order->get($params);
});
//retrieve one
$router->get("/ordenes/:order", function ($params) {
	$order = new OrderController;
	$order->retrieve($params);
});
// create one
$router->post("/ordenes", function ($params) {
	$order = new OrderController;
	$order->create($params);
});
/**
 *  Orders Endpoints
 */

/**
 *  Receipts Endpoints
 */
//get all
$router->get("/recibos", function ($params) {
	$receipt = new ReceiptController;
	$receipt->get($params);
});
//retrieve one
$router->get("/recibos/:receipt", function ($params) {
	$receipt = new ReceiptController;
	$receipt->retrieve($params);
});
/**
 *  Receipts Endpoints
 */

/**
 *  Charges Endpoints
 */
//get all
$router->get("/cobros", function ($params) {
	$charge = new ChargeController;
	$charge->get($params);
});
//retrieve one
$router->get("/cobros/:charge", function ($params) {
	$charge = new ChargeController;
	$charge->retrieve($params);
});
//create one
$router->post("/cobros", function ($params) {
	$charge = new ChargeController;
	$charge->create($params);
});
/**
 *  Charges Endpoints
 */
