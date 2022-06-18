<?php

namespace api\Models;

use base\models\Model;
use Error;

class StudentStatus extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Get all status 
	 */
	public function getAll($params)
	{
		// query to get all detail of a Student
		$query = "SELECT
			idretiro as status_id,
			nombreretiro as status
			FROM retiros";

		// retrieve data and save in an variable
		$data = parent::query($query);

		return $data;
	}
}
