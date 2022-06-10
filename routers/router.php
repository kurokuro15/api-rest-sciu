<?php

/**
 * Definiremos las rutas acá.
 */

use api\Controllers\CategoryController;
use api\controllers\ChargeController;
use api\Controllers\OrderController;
use api\controllers\ProductController;
use api\controllers\ReceiptController;
use api\Controllers\ReportController;
use api\Controllers\StudentController;
use base\controllers\UserController;
use base\middleware\AuthenticationMiddleware;


/**
 * Login Endpoint
 */
$router->post("/login", function ($params) {
	$auth = new AuthenticationMiddleware;
	$auth->authUser($params);
});
$router->put("/login", function($params){
	$auth = new AuthenticationMiddleware;
	$auth->authy($params);
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

