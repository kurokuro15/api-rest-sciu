<?php

namespace api;
// definimos ROOT y traemos el archivo Autoload.php
define('__ROOT__', dirname(__FILE__, 2));
require(__ROOT__ . '/base/helpers/Autoload.php');

/**
 * Este index será la ruta principal que servirá la API.
 */

use base\https\Request;
use base\routers\Router;
use base\https\Response;
use Throwable;

$request = new Request;
$response = new Response;


// Seteamos los Header necesarios.
$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$response->setHeader('Content-Type: application/json; charset=UTF-8');

// resquest. Acá tocaría validad que sea un método válido CREO...
$url = explode("?", $_SERVER["REQUEST_URI"], 2);
//picamos la url
$method = $_SERVER["REQUEST_METHOD"];

// Se crea la ruta e intentamos ejecutarlas. Si algo sale mal, devolvemos un error message
$router = new Router($url[0], $method);
require('../routers/router.php');

try {
	$router->run();
} catch (Throwable $err) {
	// responde con el error en formato json y el código de error HTTP pasado en el error
	$response->send(["error" => "{$err->getMessage()}"], $err->getCode());
} finally {
	$response->render();
}
