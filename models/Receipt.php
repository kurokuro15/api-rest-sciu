<?php
namespace api\Models;
use \base\models\Model;
use Error;
use ValueError;

class Receipt extends Model {

	/**
	 * get All receipts from a Student
	 */
	function getByStudent($cedula){
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new ValueError("Cedula not is a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];

		$query = "SELECT
		fechapago AS reg_date,
		factura AS receipt_number,
		SUM(pagos.monto) AS amount,
		anulado AS canceled
	FROM
		emisiones
	JOIN pagos ON
		idregistro = idregistr
	WHERE
		id_cedul = :cedula
	GROUP BY
		factura,
		anulado,
		fechapago
	ORDER BY
		fechapago DESC;";
	
	$data = parent::query($query,$param);

	if (count($data)<1) {
		throw new Error("data not found", 404);
	}
	// if all it's okay return the student.
	return $data;
	}
}
