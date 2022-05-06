<?php
/**
 * Definiremos las rutas acá.
 */

 $router->get("/estudiantes", function($params){
		print_r("Hola desde la ruta estudiantes");
		$response = $GLOBALS['response'];
		if($response){ 
			$response->status(200);
			$response->send(["Hola" => "Adiós"]);
		}
 });

 $router->get("/estudiantes/:cedula", function($params){
	if(!empty($params)) 
		print_r($params['cedula']);
 });

 $router->get("/ordenes", function($params){
	 print_r('Hola desde la ruta Ordenes');
	if(!empty($params))
		print_r('Hola desde la ruta Ordenes con el estudiante: <br>');
		print_r('Hola desde la ruta Ordenes con el recibo: <br>');	
 });
 $router->get("/ordenes/:id_registro", function($params){
	if(!empty($params)) 
		print_r($params['id_registro']);
 });

 $router->get("/recibos", function($params){
	print_r('Hola desde la ruta Recibos');
	if(!empty($params)) 
		print_r('Hola desde la ruta recibos con el estudiante: <br>');
		print_r('Hola desde la ruta recibos con la orden: <br>');	
 });

 $router->get("/recibos/:id", function($params){
	if(!empty($params)) 
		print_r($params['id']);
 });