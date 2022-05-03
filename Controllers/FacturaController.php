<?php
namespace api\Controllers;

use \api\Models\Factura;

use ErrorException;
/**
 * Clase controladora de factura para pruebas
 */

class FacturaController {
	// podría inyectarse como dependencia FacturaModel y ResponseModel
	protected $_factura;
function __construct()
{
	$this->_factura = new Factura;	


}

public function get() {
	if(!empty($_GET['id'])){
		$id = $_GET['id'];
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
	}
}

}