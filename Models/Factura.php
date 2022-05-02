<?php
 require_once '/Models/Model.php';
/**
 * Modelo de la clase factura... para pruebas
 */

 class Factura extends Model {
function __construct()
{
	parent::__construct();
}

public function getFactura($id) {
	$query = "SELECT id_cedula as 'cedula', nombre1 as 'nombre', factura, SUM(pagos.monto) as monto FROM alumnos 
	JOIN emisiones ON id_cedula = id_cedul JOIN pagos ON idregistro = idregistr 
	WHERE id_cedula = :id 
	GROUP BY id_cedula, nombre1, factura;";
	if(isset($id)) $param = ["id" => $id];
	$results = parent::query($query,$param);

	if(!$results) {
		throw new ErrorException('Not Data',0);
	}
}

}