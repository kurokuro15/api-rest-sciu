<?php

namespace api\Models;

use base\models\Model;
use Error;

class Coordination extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve a Coordination by id
	 */
	public function get($id)
	{
		//Validate param
		if (!isset($id) || !is_numeric($id)) {
			throw new Error("Identity of coordination is not a valid number", 400);
		}
		// map param in a array
		$param = [":id" => $id];

		// query to get all detail of a Student
		$query = "SELECT
			idcoordinacion as id,
			nombrecoordinacion as coordination
			FROM coordinaciones
			WHERE 
				idcoordinacion = :id";

		$data = parent::query($query, $param);

		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data[0];
	}
	
	public function getAll()
	{
		$query = "SELECT
			idcoordinacion as id,
			nombrecoordinacion as coordination
			FROM coordinaciones";

		$data = parent::query($query);

		return $data;
	}

	public function create($params)
	{
		//Validate param
		if (!isset($params["coordination"])) {
			throw new Error("Coordination is not valid", 400);
		}
		// map param in a array
		$param = [":coordination" => $params["coordination"], "id" => $this->getLastId() + 1];
		$query = "INSERT INTO coordinaciones (idcoordinacion, nombrecoordinacion) VALUES (:id, :coordination) returning idcoordinacion as id";
		$data = parent::query($query, $param);
		return $data[0]['id'];
	}

	public function update($params)
	{
		//Validate param
		if (!isset($params["id"]) || !is_numeric($params["id"])) {
			throw new Error("Identity of coordination is not a valid number", 400);
		}
		if (!isset($params["coordination"])) {
			throw new Error("Coordination is not valid", 400);
		}
		// map param in a array
		$param = [":coordination" => $params["coordination"], ":id" => $params["id"]];
		$query = "UPDATE coordinaciones SET nombrecoordinacion = :coordination WHERE idcoordinacion = :id returning idcoordinacion as id";
		$data = parent::query($query, $param);
		return $data[0]['id'];
	}

	public function delete($id)
	{
		//Validate param
		if (!isset($id) || !is_numeric($id)) {
			throw new Error("Identity of coordination is not a valid number", 400);
		}
		// map param in a array
		$param = [":id" => $id];
		$query = "DELETE FROM coordinaciones WHERE idcoordinacion = :id";
		$data = parent::query($query, $param);
		return $data;
	}

	private function getLastId()
	{
		$query = "SELECT idcoordinacion as id FROM coordinaciones ORDER BY idcoordinacion DESC LIMIT 1";
		$data = parent::query($query);
		return $data[0]["id"];
	}
}
