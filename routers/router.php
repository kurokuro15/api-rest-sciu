<?php

/**
 * Definiremos las rutas acá.
 */

use api\Controllers\CareerController;
use api\Controllers\CategoryController;
use api\controllers\ChargeController;
use api\Controllers\CoordinationController;
use api\controllers\ExchangeController;
use api\Controllers\OrderController;
use api\Controllers\ParameterController;
use api\controllers\ProductController;
use api\controllers\ReceiptController;
use api\Controllers\ReportController;
use api\Controllers\StudentController;
use api\Controllers\StudentStatusController;
use base\controllers\UserController;
use base\middleware\AuthenticationMiddleware;


/**
 * Login Endpoint
 */
$router->post("/login", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authUser($params);
});
$router->put("/login", function ($params) {
	$auth = new AuthenticationMiddleware;
	$params["auth"] = $auth;
	$auth->authy($params, function ($params) {
		$params["auth"]->refreshToken($params);
	});
});
/**
 * Login Endpoint
 */


/**
 * Users Endpoints
 */
$router->post("/usuarios", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$users = new UserController;
		$users->createUser($params);
	});
});
$router->get("/usuarios", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$users = new UserController;
		$users->get($params);
	});
});
$router->get("/usuarios/:username", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$users = new UserController;
		$users->retrieve($params);
	});
});
$router->put("/usuarios", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authQuestions($params);
});
$router->delete("/usuarios/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$users = new UserController;
		$users->delete($params);
	});
});
/**
 * Users Endpoints
 */


/**
 *  Students Endpoints
 */
$router->get("/estudiantes", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$student = new StudentController;
		$student->get($params);
	});
});
$router->get("/estudiantes/:cedula", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$student = new StudentController;
		$student->retrieve($params);
	});
});
$router->put("/estudiantes/:cedula", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$student = new StudentController;
		$student->put($params);
	});
});
/**
 *  Students Endpoints
 */


/**
 *  Orders Endpoints
 */
$router->get("/ordenes", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$order = new OrderController;
		$order->get($params);
	});
});
$router->get("/ordenes/:order", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$order = new OrderController;
		$order->retrieve($params);
	});
});
$router->post("/ordenes", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$order = new OrderController;
		$order->create($params);
	});
});
/**
 *  Orders Endpoints
 */


/**
 *  Receipts Endpoints
 */
$router->get("/recibos", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$receipt = new ReceiptController;
		$receipt->get($params);
	});
});
$router->get("/recibos/:receipt", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$receipt = new ReceiptController;
		$receipt->retrieve($params);
	});
});
$router->delete("/recibos/:receipt", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$receipt = new ReceiptController;
		$receipt->delete($params);
	});
});
/**
 *  Receipts Endpoints
 */


/**
 *  Charges Endpoints
 */
$router->get("/cobros", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$charge = new ChargeController;
		$charge->get($params);
	});
});
$router->get("/cobros/:charge", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$charge = new ChargeController;
		$charge->retrieve($params);
	});
});
$router->post("/cobros", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$charge = new ChargeController;
		$charge->create($params);
	});
});
/**
 *  Charges Endpoints
 */


/**
 * Reports endpoint
 */
$router->get("/reportes", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$report = new ReportController;
		$report->get($params);
	});
});
$router->get("/reportes/:report", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$report = new ReportController;
		$report->detailed($params);
	});
});
/**
 * Reports endpoint
 */



/**
 *  Categories Endpoints
 */
$router->get("/categorias", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$category = new CategoryController;
		$category->get($params);
	});
});
$router->get("/categorias/:category", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$category = new CategoryController;
		$category->retrieve($params);
	});
});
$router->post("/categorias", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$category = new CategoryController;
		$category->create($params);
	});
});
$router->put("/categorias/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$category = new CategoryController;
		$category->update($params);
	});
});
$router->delete("/categorias/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$category = new CategoryController;
		$category->delete($params);
	});
});
/**
 *  Categories Endpoints
 */


/**
 * Products Endpoints
 */
$router->get("/productos", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$product = new ProductController;
		$product->get($params);
	});
});
$router->get("/productos/:product", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$product = new ProductController;
		$product->retrieve($params);
	});
});
$router->post("/productos", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$product = new ProductController;
		$product->create($params);
	});
});
$router->put("/productos/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$product = new ProductController;
		$product->update($params);
	});
});
$router->delete("/productos/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$product = new ProductController;
		$product->delete($params);
	});
});
/**
 * Products Endpoints
 */

/**
 * Students Status Endpoints
 */
$router->get("/status", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$status = new StudentStatusController;
		$status->get($params);
	});
});
$router->get("/status/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$status = new StudentStatusController;
		$status->retrieve($params);
	});
});
$router->put("/status/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$status = new StudentStatusController;
		$status->put($params);
	});
});
$router->post("/status", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$status = new StudentStatusController;
		$status->post($params);
	});
});
$router->delete("/status/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$status = new StudentStatusController;
		$status->delete($params);
	});
});
/**
 * Students Status Endpoints
 */

/**
 * Careers Endpoints
 */
$router->get("/carreras/:career", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$career = new CareerController;
		$career->retrieve($params);
	});
});
$router->get("/carreras", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$career = new CareerController;
		$career->get($params);
	});
});
$router->post("/carreras", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$career = new CareerController;
		$career->create($params);
	});
});
$router->put("/carreras/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$career = new CareerController;
		$career->update($params);
	});
});
$router->delete("/carreras/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$career = new CareerController;
		$career->delete($params);
	});
});
/**
 * Careers Endpoints
 */

/**
 * Coordinates Endpoints
 */
$router->get("/coordinaciones/:coordination", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$coordinate = new CoordinationController;
		$coordinate->retrieve($params);
	});
});
$router->get("/coordinaciones", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$coordinate = new CoordinationController;
		$coordinate->get($params);
	});
});
$router->post("/coordinaciones", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$coordinate = new CoordinationController;
		$coordinate->create($params);
	});
});
$router->put("/coordinaciones/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$coordinate = new CoordinationController;
		$coordinate->update($params);
	});
});
$router->delete("/coordinaciones/:id", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$coordinate = new CoordinationController;
		$coordinate->delete($params);
	});
});

$router->get("/parametros", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$parameter = new ParameterController;
		$parameter->get($params);
	});
});

$router->post("/parametros", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$parameter = new ParameterController;
		$parameter->post($params);
	});
});

$router->put("/parametros/:seed", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$parameter = new ParameterController;
		$parameter->put($params);
	});
});

$router->delete("/parametros/:seed", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$parameter = new ParameterController;
		$parameter->delete($params);
	});
});


/**
 * Exchange Endpoints
 */
$router->get('/intercambios/:id', function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$exchange = new ExchangeController;
		$exchange->retrieve($params);
	});
});
$router->get('/intercambios', function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$exchange = new ExchangeController;
		$exchange->get($params);
	});
});
$router->post('/intercambios', function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$exchange = new ExchangeController;
		$exchange->post($params);
	});
});
$router->put('/intercambios/:id', function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$exchange = new ExchangeController;
		$exchange->put($params);
	});
});
$router->delete('/intercambios/:id', function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authy($params,	function ($params) {
		$exchange = new ExchangeController;
		$exchange->delete($params);
	});
});
/**
 * Exchange Endpoints
 */
