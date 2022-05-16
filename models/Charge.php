<?php

namespace api\Models;

use \base\models\Model;
use Error;
use Exception;
use ValueError;

class Charge extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve a Charge record
	 */
	public function get($id)
	{
		//Validate param
		if (!isset($id) || (int) $id === 0) {
			throw new ValueError("Charge identity is not a valid number", 401);
		}
		// map param in a array
		$param = [":id" => $id];
		$query = "SELECT 
				idpago as id, 
				idregistr as order_id, 
				fechapago as reg_date, 
				monto as amount, 
				factura as receipt_number, 
				registrador as username, 
				anulado as canceled, 
				autorizacion as autorized,
				idtipopag as payment_description,
				tipopago as payment,
				tipospago.fecha as payment_date
			FROM 
				pagos
			LEFT JOIN tipospago ON
				idtipopag = idtipopago
			WHERE 
				idpago = :id;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (is_array($data)) {
			// Map properties of class to use this info. And return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data[0];
	}

	/**
	 * Get all Charge record by Student
	 * lest add pagination
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new ValueError("Cedula is not a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];
		$query = "SELECT
			idpago as id,
			idregistr as order_id,
			fechapago as reg_date,
			pagos.monto as amount,
			factura as receipt_number,
			registrador as username,
			anulado as canceled,
			autorizacion as autorized,
			idtipopag as payment_description,
			tipopago as payment,
			tipospago.fecha as payment_date
		FROM
			pagos
		left join tipospago on
			idtipopag = idtipopago
		join emisiones on
			idregistr = idregistro
		where
			id_cedul = :cedula
		order by 
			fechapago desc, 
			fecha desc;";
		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}
	/**
	 * Get All Charge Records
	 */
	public function getAll($pagination)
	{
	}

	/**
	 * Get All Charge record by Receipt
	 */
	public function getByReceipt($receipt)
	{
		//Validate param
		if (!isset($receipt) || (int) $receipt === 0) {
			throw new ValueError("receipt number is not a valid number", 401);
		}
		// map param in a array
		$param = [":receipt" => $receipt];

		$query = "SELECT
				idpago as id,
				idregistr as order_id,
				fechapago as reg_date,
				monto as amount,
				factura as receipt_number,
				registrador as username,
				anulado as canceled,
				autorizacion as autorized,
				idtipopag as payment_description,
				tipopago as payment,
				fecha as payment_date
			FROM
				pagos
			left join tipospago on
				idtipopag = idtipopago
			where
				factura = :receipt
			order by 
				fechapago desc, 
				fecha desc;";
		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}

	/**
	 * Create a Charge record
	 */
	public function insert($charge)
	{
		$params = [];
		// validate data
		$required = [
			"order_id",
			"reg_date",
			"amount",
			"receipt_number",
			"username",
			"canceled",
			"autorized",
			"payment_description",
			"payment"
		];

		foreach ($required as $value) {
			if (empty($charge[$value]) && $charge[$value] !== 0) {
				throw new Exception("no se a conseguido el campo $value", 403);
			};
			$params[$value] = $charge[$value];
		}
		if (!isset($params['receipt_number']) || (int) $params['receipt_number'] === 0) {
			throw new ValueError("the receipt_number is not a valid receipt number", 401);
		}
		//set reg_date -4 GMT like 2022-05-15 17:05 Y-M-D H:mm
		$params['reg_date'] = date('Y-m-d H:i');
		// Prepare query
		$query = "";
		//insert data 
		$result = parent::nonQuery($query, $params);

		return $result;
		//return success messange or error msg
	}
	/**
	 * Update a Charge record
	 */
	public function update($charge)
	{
	}
	/**
	 * Delete a Charge record
	 */
	function delete($charge)
	{
	}
}
