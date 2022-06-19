<?php

namespace api\Models;

use base\models\Model;
use Error;

class PaymentMethod extends Model
{
	function getAll($params)
	{
		$query = "SELECT DISTINCT tipopago FROM tipospago;";
		$data = parent::query($query);

		// validate 
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data;
	}
	// Retrieve por implementar
	// Create por implementar
	// Update por implementar
	// Delete por implementar
}
