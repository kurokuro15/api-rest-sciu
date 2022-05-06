<?php
namespace api\Models;

use \base\Models\Model;
use ErrorException;
/**
 * Modelo de la clase factura... para pruebas
 */
 class Factura extends Model {
function __construct()
{
	parent::__construct();
}

public function getFactura($id) {
	$query = "SELECT id_cedula, nombre1, factura, SUM(pagos.monto) as monto FROM alumnos 
	JOIN emisiones ON id_cedula = id_cedul JOIN pagos ON idregistro = idregistr 
	WHERE id_cedula = $id 
	GROUP BY id_cedula, nombre1, factura;";
	
	$results = parent::query($query);

	if(!$results) {
		throw new ErrorException('Not Data',0);
	} else {
		return $results;
	}
	
}

}