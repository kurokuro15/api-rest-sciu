<?php

namespace api\Models;

use \base\models\Model;
use Error;
use Exception;

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
		if (!isset($id) || (empty($id) && !is_numeric($id))) {
			throw new Error("Charge identity is not a valid number", 400);
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
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		// Map properties of class to use this info. And return object.
		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		}

		return $data[0];
	}

	/**
	 * Get all Charge record by Student
	 * lest add pagination
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($id) || (empty($id) && !is_numeric($id))) {
			throw new Error("Cedula is not a valid number", 400);
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

		$data = parent::query($query, $param);

		//validate
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data;
	}
	/**
	 * Get All Charge Records por implementar
	 */
	public function getAll($params)
	{
		// will be created
	}

	/**
	 * Get All Charge record by Receipt
	 */
	public function getByReceipt($receipt)
	{
		//Validate param
		if (!isset($receipt) || (empty($receipt) && !is_numeric($receipt))) {
			throw new Error("receipt number is not a valid number", 400);
		}
		// map param in a array
		$param = [":receipt" => $receipt];

		$query = "SELECT
				idpago as id,
				idregistr as order_id,
				fechapago as reg_date,
				p.monto as amount,
				e.concepto as concept,
				factura as receipt_number,
				registrador as username,
				anulado as canceled,
				autorizacion as autorized,
				idtipopag as payment_description,
				tipopago as payment,
				fecha as payment_date
			FROM
				pagos p
			join emisiones e on 
				idregistr  = idregistro
			left join tipospago on
				idtipopag = idtipopago
			where
				factura = :receipt
			order by 
				fechapago desc, 
				fecha desc;";
		// retrieve data and save in an variable
		$data = parent::query($query, $param);

		//validate
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

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
			"amount",
			"receipt_number",
			"username",
			"deposit", /*idtipopago  */
		];

		/** Optional field */
		if (isset($charge["autorized"]) && !$this->is_blank($charge["autorized"])) {
			$params["autorized"] = $charge["autorized"];
		} else {
			$params["autorized"] = null;
		}

		// Validate all others required fields
		foreach ($required as $value) {
			if (!isset($charge[$value]) && $this->is_blank($charge[$value])) {
				throw new Exception("no se a conseguido el campo $value", 400);
			}
			$params[$value] = $charge[$value];
		}

		// Validamos que sean números válidos 
		if (!isset($params['receipt_number']) || (empty($params['receipt_number']) && !is_numeric($params['receipt_number']))) {
			throw new Error("the receipt_number is not a valid receipt number", 400);
		}

		if (!isset($params['order_id']) || (empty($params['order_id']) && !is_numeric($params['order_id']))) {
			throw new Error("the order_id is not a valid receipt number", 400);
		}
		// seteamos la fecha :D
		//set reg_date -4 GMT like 2022-05-15 17:05 Y-M-D H:mm
		$params['reg_date'] = date('Y-m-d H:i');

		/** Esto se hace así para 'soportar' las fallas actuales del sistema
		 * se tiene que migrar la mayor´parte de la data y seccionar esto en
		 * a otros campos
		 */
		$query = "INSERT 
		INTO pagos(
			idregistr,
			fechapago, /**Generado en back */
			monto,
			idtipopag,
			factura,	/**Generado en back */
			registrador,
			autorizacion /**Opcional*/
			) 
		VALUES (
			:order_id,
			:reg_date,
			:amount,
			:deposit,
			:receipt_number,
			:username,
			:autorized
			)";

		//insert data 
		$result = parent::nonQuery($query, $params);

		return $result;
	}

	/**
	 * Update a Charge record por implementar
	 */
	public function update($charge)
	{
	}
	/**
	 * Delete a Charge record por implementar
	 */
	function delete($charge)
	{
	}

	function getLastReceipt()
	{
		$query = "select
		case
			when max(factura) is null then 1
			else max(factura)
		end as receipt_number
	from
		pagos";

		$data = parent::query($query);

		if (count($data)  <= 0)
			throw new Error("can't get last receipt number", 404);

		return $data[0];
	}
}
