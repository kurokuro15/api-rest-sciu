<?php
namespace api\controllers;

use api\Models\Factura;
use api\Helper\RouterHelper;
use ErrorException;
/**
 * Clase controladora de factura para pruebas
 */

class FacturaController {
	// podría inyectarse como dependencia FacturaModel y ResponseModel
	protected $_factura;
	protected $router;
function __construct()
{
	$this->_factura = new Factura;
 $this->router = new RouterHelper();
}

public function index($id) {
	$this->router->vista('factura',$id);
}

public function get($id) {
	if(!empty($id)) {
		try{
			$body = $this->_factura->getFactura($id);
			$response = [
				"status"=> "ok",
				"status_code" => "200",
				"result"=> $body
			];
			
			return $response;

		} catch(ErrorException $err) {
			//esto hay que formatearlo más 
			return $err;
		}
	} else {
		echo 'no hay id';
	}
}

}