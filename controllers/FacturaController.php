<?php
namespace api\controllers;

use api\Models\Factura;
use ValueError;

/**
 * Clase controladora de factura para pruebas
 */

class FacturaController {
	// podría inyectarse como dependencia FacturaModel y ResponseModel
	protected $_factura;
function __construct()
{
	$this->factura = new Factura;
}

public function get($params) {
	if(isset($params['cedula']))
		$cedula = $params['cedula'];
	else
		throw new ValueError("cédula no seteada.");
		
	if(!empty($cedula)) {
		try{
			$res = $this->factura->getFactura($cedula);
			return $res;
		} catch(\Error $err) {
			//esto hay que formatearlo más 
			return $err->message;
		}
	} else {
		echo 'no hay cedula';
	}
}

}