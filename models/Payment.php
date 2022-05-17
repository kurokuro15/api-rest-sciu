<?php

namespace api\Models;

use \base\models\Model;
use Error;
use Exception;
use ValueError;

/**
 * Model class to 'tipospago' table
 */
class Payment extends Model
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
		if ($this->is_blank($id)) {
			throw new ValueError("Charge identity is not a valid number", 401);
		}
		// map param in a array
		$param = [":id" => $id];

		$query = "SELECT
			idtipopago as deposit,
			fecha as reg_date,
			banco as bank,
			concepto as concept,
			tipopago as payment
		from
			tipospago t
		where
			idtipopago = :id;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param, false);
		//validate data
		if (is_array($data)) {
			// Map properties of class to use this info. And return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new Error("not found", 404);
		}
		// if all it's okay return the student.
		return $data[0];
	}
	/**
	 * Create a Payment record
	 */
	public function insert($payment)
	{
		$params = [];

		$required = [
			/**idtipopago */
			"deposit",
			/**tipopago */
			"method"
		];

		$nonrequired = [
			/**fecha */
			"payment_date",
			/**banco */
			"bank",
			/**concepto */
			"concept",
		];

		foreach ($required as $value) {
			//validamos que no estén vacíos. 
			if (!isset($payment[$value]) && $this->is_blank($payment[$value])) {
				throw new Exception("no se a conseguido el campo $value", 403);
			}
			$params[$value] = $payment[$value];
		}

		foreach ($nonrequired as $value) {
			//validamos que no estén vacíos, si lo están los definimos como NULL. 
			if (!isset($payment[$value])) {
				$params[$value] = null;
					continue;
				}
				$params[$value] = $payment[$value];
			}

		// sí está en null payment_date, entonces seteamos la hora actual.
		if ($params['payment_date'] === null) {
			//set payment_date -4 GMT like 2022-05-15 17:05 Y-M-D H:mm
			$params['payment_date'] = date('Y-m-d H:i');
		}
		/** Esto se hace así para 'soportar' las fallas actuales del sistema
		 * se tiene que migrar la mayor´parte de la data y seccionar esto en
		 * concepto y banco
		 */
		$query = "INSERT
			INTO
			tipospago (
			idtipopago,
			fecha,
			banco,
			concepto,
			tipopago)
		VALUES (
			:deposit,
			:payment_date,
			:bank,
			:concept,
			:method) /**idtipopago -> detalles del método */
			RETURNING idtipopago;";
		//insert data 
		$data = parent::query($query, $params);

		return $data[0];
		//return success messange or error msg
	}
}
