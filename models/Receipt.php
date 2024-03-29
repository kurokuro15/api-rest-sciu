<?php

namespace api\Models;

use \base\models\Model;
use Error;

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
		if (!isset($receipt) || (empty($receipt) && !is_numeric($receipt))) {
			throw new Error("Receipt number is not a valid number", 400);
		}
		// map param in a array
		$param = [":receipt" => $receipt];
		$query = "SELECT
					factura as receipt,
					SUM(pagos.monto) as amount,
					fechapago as payment_date,
					registrador as username,
					id_cedul as cedula,
					CONCAT(apellido1 || ' ' || nombre1 ) as nombre,
					anulado AS canceled
				from
					pagos
				join emisiones on
					idregistro = idregistr
					join alumnos on
					id_cedul = id_cedula
				where 
					factura = :receipt
				group by
					factura,
					fechapago,
					registrador,
					id_cedul,
					nombre1,
					nombre2,
					apellido1,
					apellido2,
					anulado ";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);

		//validate data
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		// we map properties of class to use this info. And send return object.
		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
			// if all it's okay return the receipt.
		}

		return $data;
	}
	/**
	 * get All receipts from a Student
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (empty($cedula) && !is_numeric($cedula))) {
			throw new Error("Cedula not is a valid number", 400);
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
		// validate
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data;
	}
	// get all receipts
	public function getAll($params)
	{
		$query = "SELECT
					factura as receipt,
					SUM(pagos.monto) as amount,
					fechapago as payment_date,
					registrador as username,
					id_cedul as cedula,
					CONCAT(apellido1 || ' ' || nombre1 ) as nombre,
					anulado AS canceled
				from
					pagos
				join emisiones on
					idregistro = idregistr
					join alumnos on
					id_cedul = id_cedula
				group by
					factura,
					fechapago,
					registrador,
					id_cedul,
					nombre1,
					nombre2,
					apellido1,
					apellido2,
					anulado 
					ORDER BY 
					fechapago DESC, 
					factura DESC, 
					id_cedul DESC";

		//Add pagination to query
		$pages = $this->pagination($params);
		$param = [];
		if(count($pages) > 0) {	
			$pages["count"] = $this->count($query);
			$query .= $pages['placeholder'];
			$param = $pages['current'];
			$this->pages = $pages;
		}
		$data = parent::query($query,$param);
		// less the meta data (next and prev arrays)
		return $data;
	}
	//Update por implementar
	//Delete (¿anular?) por implementar
	public function delete($receipt)
	{
		if (!isset($receipt) || (empty($receipt) && !is_numeric($receipt)))
			throw new Error("Receipt number is not a valid number", 400);

		$params = [":receipt" => $receipt];
		$query = "UPDATE pagos SET anulado = true WHERE factura = :receipt returning factura as receipt";

		$data = parent::query($query, $params);

		return  $data[0];
	}
}
