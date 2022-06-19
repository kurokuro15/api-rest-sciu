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
			idretiro as id,
			nombreretiro as status
			FROM retiros";

		// retrieve data and save in an variable
		$data = parent::query($query);

		return $data;
	}

	public function get($id)
	{
		$query = "SELECT 
			idretiro as id,
			nombreretiro as status
			FROM retiros
			WHERE idretiro = :id";

		if (!isset($id) || (empty($id) && !is_numeric($id))) {
			throw new Error("id is not a valid number", 400);
		}

		$param = [":id" => $id];
		$data = parent::query($query, $param);

		foreach ($data as $prop => $value) {
			$this->$prop = $value;
		}
		return $data[0];
	}
	public function update($status)
	{
		$query = "UPDATE retiros SET nombreretiro = :status WHERE idretiro = :id returning idretiro as id";

		if (!isset($status["id"]) || (empty($status["id"]) && !is_numeric($status["id"]))) {
			throw new Error("id is not a valid number", 400);
		}
		if (!isset($status["status"]))
			throw new Error("status is not defined", 400);

		$param = [":id" => $status["id"], ":status" => $status["status"]];
		$data = parent::query($query, $param);
		return $data[0];
	}
	public function create($status)
	{
		$query = "INSERT INTO retiros(idretiro,nombreretiro) VALUES(:id, :status) returning idretiro as id";

		if (!isset($status["status"]))
			throw new Error("status is not defined", 400);

		$param = [":status" => $status["status"], "id" => ($this->getLastId() + 1)];
		$data = parent::query($query, $param);
		return $data[0];
	}

	public function delete($status)
	{
		if (empty($status['id'])) {
			throw new Error("Error, falta el identificador del status", 400);
		}
		$query = "DELETE FROM retiros WHERE idretiro = :id";
		$params['id'] = $status['id'];
		$result = parent::query($query, $params);
		return $result;
	}

	function getLastId()
	{
		$query = "select
		case
			when max(idretiro) is null then 1
			else max(idretiro)
		end as id
	from
		retiros";

		$data = parent::query($query);

		if (count($data)  <= 0)
			throw new Error("can't get last receipt number", 404);

		return $data[0]['id'];
	}
}
