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
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}
}
