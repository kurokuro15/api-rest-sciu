<?php

namespace api\Models;

use \base\models\Model;
use Error;
use ValueError;

class Receipt extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * retrieve a receipt by id
	 */
	public function get($receipt)
	{
		//Validate param
		if (!isset($receipt) || (int) $receipt === 0) {
			throw new ValueError("Receipt number is not a valid number", 401);
		}
		// map param in a array
		$param = [":receipt" => $receipt];
		$query = "SELECT
					factura as receipt,
					SUM(pagos.monto) as total,
					fechapago as payment_date,
					registrador as username,
					id_cedul as cedula
				from
					pagos
				join emisiones on
					idregistro = idregistr
				where 
					factura = :receipt
				group by
					factura,
					fechapago,
					registrador,
					id_cedul;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (is_array($data)) {
			// we map properties of class to use this info. And send return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the order.
		return $data[0];
	}
	/**
	 * get All receipts from a Student
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new ValueError("Cedula not is a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];

		// query to search all receipts by student's cedula
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

		$data = parent::query($query, $param);

		// Validate data
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}
}
