<?php
namespace api;
/**
 * Este index será la ruta principal que servirá la API.
 */

define('__ROOT__', dirname(dirname(__FILE__)));
require (__ROOT__ . '/helpers/Autoload.php');

spl_autoload_register('\api\Helpers\autoload');

use base\routers\Router;
use base\https\Response;
$response = new Response;
// Seteamos los Header necesarios.
$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$response->setHeader('Content-Type: application/json; charset=UTF-8');
// resquest. Acá tocaría validad que sea un método válido CREO...
$url = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router = new Router($url,$method);
require('../routers/router.php');

$router->run();
/**
 * 
 * ##############################################################################
 * FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY.
 * ##############################################################################
 * 
 */
// // instancio el controller
// $factura = new FacturaController;
// //Ruta de la home
// $home = "/api/";

// //Quito la home de la ruta de la barra de direcciones
// $ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);
// $pattern = 'factura/:cedula';
// $patternOrden = '/ordenes';
// $patternOrderId = "/ordenes/:id";
// $patternArr = [$pattern, $patternOrden, $patternOrderId];
// //Creo el array de ruta (filtrando los vacíos)
// // $array_ruta = array_filter(explode("/", $ruta));
// // Acá picamos si hay '/' o '?' o '&' para ir extrayendo la rutas, sub-rutas y queryParams
// $array_link = array_filter(preg_split("/[\/ ? &]+/", $ruta, 3));
// $arrPattern =  array_filter(preg_split("/[\/ ? &]+/", $pattern, 3));
// // estudiante/26576198/ordenes/18540/
// //divide string de patrones en arreglos de la forma [endpoint, parameter, otherEndpoint] muta la variable pasada.
// function splitPattern($patterns)
// {
//     if (is_array($patterns)) {
//         foreach ($patterns as $key => $pattern) {
//             $patterns[$key] =  array_filter(preg_split("/[\/ ? &]+/", $pattern, 3));
//             if (!empty($patterns[$key][2])) {
//                 splitPattern($patterns[$key][2]);
//             }
//         }
//     } else {
//         $patterns = array_filter(preg_split("/[\/ ? &]+/", $patterns, 3));
//             if (!empty($patterns[2])) {
//                 splitPattern($patterns[2]);
//             }
//     }
// }
// // Función de comparación
// function sortMatchPatterns($arr)
// {
//     if (usort($arr, function ($a, $b) {
//         if ($a == $b) {
//             return 0;
//         }
//         return (count($a) < count($b)) ? 1 : -1;
//     }))
//         return $arr;
// }

// // foreach($array_link as $key => $value) { 
// //     if(str_contains("=",$value)) {
// //         $split = preg_split("/[=]+/", $value);
// //         $params[] = [$split[0] => $split[1]];
// //     }
// // }
// // url : host/api/estudiante/:cedula/factura/:factura
// //pica si hay signo = dentro del segundo elemento del arreglo
// // $split = explode('=', $array_link[1]);
// // if($split) $param = array( $split[0] => $split[1]);
// // validamos que el primer elemento de ambos arreglos (url y patrón) sean iguales

// //dividimos la url en 3 partes según los slashes
// $array_link = array_filter(preg_split("/[\/ ? &]+/", $url, 3));
// // validamos que el endpoint base sea el correcto
// if ($array_link[0] === $arrPattern[0]) {
//     //validamos que el segundo argumento tenga un parámetro...
//     if (str_contains($arrPattern[1], ":")) {
//         //si ambos tienen un segundo elemento entonces...
//         if (!empty($arrPattern[1]) && !empty($array_link[1])) {
//             //validamos que el elemento que nos pasan es entero
//             if (intval($array_link[1]) != 0) {
//                 // Seteamos $param con ambos
//                 $key = array_filter(preg_split("/[:]+/", $arrPattern[1]));
//                 $param = array(
//                     $key[1] => $array_link[1]
//                 );
//             }
//         }
//     }
// }


// //Decido la ruta en función de los elementos del array para factura.
// if (isset($array_link[0]) && $array_link[0] == "factura") {
//     if ($param) {
//         if ($param['cedula']) {
//             $factura->index($param['cedula']);
//         }
//     }
//     //Llamo al método ver pasándole la clave que me están pidiendo
// } else {

//     //Llamo al método por defecto del controlador
// }
/**
 * 
 * ##############################################################################
 * FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY. FIRST TRY.
 * ##############################################################################
 * 
 */




/**
 * 
 * ##############################################################################
 * SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY.
 * ##############################################################################
 * 
 */
// $routes = [
//     ["name" => "facturas", "pattern" => "/facturas"],
//     ["name" => "facturas", "pattern" => "/facturas/:id_registro"],
//     ["name" => "ordenes", "pattern" => "/ordenes/:orden"],
//     ["name" => "ordenes", "pattern" => "/ordenes"],
//     ["name" => "estudiantes", "pattern" => "/estudiantes"],
//     ["name" => "estudiantes", "pattern" => "/estudiantes/:cedula"]
// ];

// function matchRoute($routes)
// {
//     $url = $_SERVER["REQUEST_URI"];
//     //Ruta de la home
//     $home = "/api";
//     //Quito la home de la ruta de la barra de direcciones
//     $url = str_replace($home, "", $_SERVER["REQUEST_URI"]);
//     $params = [];
//     //matcheamos la url con los patrones y traemos los parámetros
//     matchUrlWithPattern($url,$routes,$params);

//     echo "los parametros en total: \n";
//     print_r($params);
// }

// // función para descubir que ruta es a la que se está llamando en la request... Y poder invocar a su callback
// function matchUrlWithPattern($url, $routes, &$params) {
//     // validamos si la url empieza por '/' (es la primera pasada) o no.
//     if(strpos( $url, "/" ) === 0){
//         $url_segments = explode("/", $url, 4);
//     } else {
//         $url_segments = explode("/", $url, 3);
//     }
//     // quito la primera posicion porque viene vacia
//     if (empty($url_segments[0]))
//         array_shift($url_segments);

//     foreach ($routes as $route) {
//         $pattern = $route["pattern"];
//         $pattern_segments = explode("/", $pattern);
//         // quito la primera posicion porque viene vacia
//         array_shift($pattern_segments);

//         // averiguamos si es el mismo EP
//         // si el primer segmento no es igual, fuera cochina
//         if ($url_segments[0] !== $pattern_segments[0]) {
//             continue;
//         }
//         // si el pattern no tiene un parámetro y la url sí, sigamos pa lante
//         if((empty($pattern_segments[1]) && !empty($url_segments[1])) || (!empty($pattern_segments[1]) && empty($url_segments[1])) ) {
//             continue;
//         }

//         // si existe y no es un int(esto debemos permitir definirlo en el pattern) valido, fuera cochina
//         if (!empty($url_segments[1]) && (int) ($url_segments[1]) === 0) {
//             echo "Parametro no valido";
//             return;
//         } 

//         if (empty($url_segments[1]) && empty($pattern_segments[1])) {
//             // si no siguen mas segmentos, llama al callback de la ruta actual
//             echo "LLamame a este mismo: " . "<br>" . $pattern . "<br>";
//             //$pattern->callback();
//             return;
//         }
//         // Si está definida la posición 1 del url y del pattern, siendo esta un int válido, entonces mapeamos y guardamos en params
//         // obtener el nombre del param
//         $param_key = ltrim($pattern_segments[1], ":");
//         // definir el parametro
//         $params[$param_key] = (int) ($url_segments[1]);

//         // si no siguen mas segmentos en la url, llama al callback de la ruta actual porque ya esta es la última.
//         // caso contrario, volvamos a picar la ruta
//         if (isset($url_segments[2])) {
//             echo "llámame a este:" . "<br>" . $pattern . "<br>";
//             print_r($params);
//             echo "<br>";
//             //$callback = $pattern->callback($params);
//             matchUrlWithPattern($url_segments[2],$routes,$params);
//         } else {
//             echo "LLamame a este mismo:" . "<br>" . $pattern . "<br>";
//             //$callback = $pattern->callback($params);
//             print_r($params);
//         }
//         // repetir de nuevo
//     }
// }
// matchRoute($routes);
/**
 * 
 * ##############################################################################
 * SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY. SECOND TRY.
 * ##############################################################################
 * 
 */