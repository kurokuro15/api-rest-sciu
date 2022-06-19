<?php

namespace api\Models;

use base\models\Model;
use Error;

class Career extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve a Career by id
	 */
	public function get($id)
	{
		//Validate param
		if (!isset($id) || !is_numeric($id)) {
			throw new Error("Identity of career is not a valid number", 400);
		}
		// map param in a array
		$param = [":id" => $id];

		// query to get all detail of a Student
		$query = "SELECT
				id_carrer AS id,
				nombrecarrera AS career,
				idcoordinacio as id_coordination
				FROM carreras
				WHERE 
					id_carrer = :id";

		$data = parent::query($query, $param);

		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data[0];
	}
	public function getAll($params)
	{
		$query = "SELECT
				id_carrer AS id,
				nombrecarrera AS career,
				idcoordinacio as id_coordination
				FROM carreras";

		$data = parent::query($query);

		return $data;
	}
	public function create($params)
	{
		//Validate param
		if (!isset($params["career"]) || !isset($params["id_coordination"])) {
			throw new Error("Career or id_coordination is not valid", 400);
		}
		// map param in a array
		$param = [
			"career" => $params["career"],
			"id_coordination" => $params["id_coordination"],
			"id" => ($this->getLastId() + 1)
		];
		// query to get all detail of a Student
		$query = "INSERT INTO carreras (id_carrer, nombrecarrera, idcoordinacio) VALUES (:id, :career, :id_coordination) returning id_carrer as id";
		$data = parent::query($query, $param);
		return $data[0]['id'];
	}
	public function update($params)
	{
		//Validate param
		if (!isset($params["id"]) || !isset($params["career"]) || !isset($params["id_coordination"])) {
			throw new Error("Identity or career or id_coordination is not valid", 400);
		}
		// map param in a array
		$param = [
			"id" => $params["id"],
			"career" => $params["career"],
			"id_coordination" => $params["id_coordination"]
		];

		$query = "UPDATE carreras SET nombrecarrera = :career, idcoordinacio = :id_coordination WHERE id_carrer = :id returning id_carrer as id";
		$data = parent::query($query, $param);
		return $data[0]['id'];
	}

	public function delete($params)
	{
		//Validate param
		if (!isset($params["id"])) {
			throw new Error("Identity is not valid", 400);
		}
		// map param in a array
		$param = [
			"id" => $params["id"]
		];
		$query = "DELETE FROM carreras WHERE id_carrer = :id";
		$data = parent::query($query, $param);
		return $data;
	}

	private function getLastId()
	{
		$query = "SELECT id_carrer as id FROM carreras ORDER BY id_carrer DESC LIMIT 1";

		$data = parent::query($query);

		if (count($data)  <= 0)
			throw new Error("can't get last career id", 404);

		return $data[0]['id'];
	}
	function getByCoordination($id) {
		$query="SELECT COUNT(*) as count FROM carreras WHERE idcoordinacio = :id";
		$param = [":id" => $id];
		$data = parent::query($query, $param);
		return $data[0]['count'];
	}
}
