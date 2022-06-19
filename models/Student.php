<?php

namespace api\Models;

use base\models\Model;
use Error;

class Student extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve a Student by 'cedula'
	 */
	public function get($cedula)
	{
		//Validate param
		if (!isset($cedula) || (empty($cedula) && !is_numeric($cedula))) {
			throw new Error("Cedula is not a valid number", 400);
		}
		// map param in a array
		$param = [":cedula" => $cedula];

		// query to get all detail of a Student
		$query = "SELECT
			id_cedula AS cedula,
			id_carrera AS career_id,
			nombrecarrera AS career,
			nombre1 AS first_name,
			nombre2 AS middle_name,
			apellido1 AS last_name,
			apellido2 AS sur_name,
			semestre AS semester,
			fechainscr AS reg_date,
			retiro AS status
		FROM
			alumnos
		JOIN carreras ON
			id_carrera = id_carrer
		WHERE
			alumnos.id_cedula = :cedula;";

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
	 * Get all Students on page of 20 by default
	 */
	public function getAll($params)
	{
		// query of some data from Students, to basic view
		$query = "SELECT
			id_cedula AS cedula,
			nombre1 AS first_name,
			apellido1 AS last_name,
			semestre AS semester,
			fechainscr AS reg_date,
			nombrecarrera AS career
		FROM
			alumnos
		JOIN carreras ON
			id_carrera = id_carrer
		ORDER BY
			reg_date DESC,
			cedula DESC";

		//Add pagination to query
		list($interval, $placeholder, $meta) = parent::pagination($params);
		$params = array_merge($params, $interval);

		// Obtenemos el total de elementos de la query y lo guardamos en meta
		$meta["count"] = $this->count($query);

		// añadimos el placeholder de paginación		
		$query .= $placeholder;
		$data = parent::query($query, $params);
		// validate that have some more zero records
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return [$data, $meta];
	}
	// Update por implementar
	public function update($student)
	{
		$query = "UPDATE alumnos SET
			nombre1 = :first_name,
			nombre2 = :middle_name,
			apellido1 = :last_name,
			apellido2 = :sur_name,
			semestre = :semester,
			fechainscr = :reg_date,
			id_carrera = :career_id,
			retiro = :status
			WHERE id_cedula = :cedula RETURNING id_cedula as cedula";

		// validate student
		if (!isset($student) || (empty($student) && !is_array($student)))
			throw new Error("Student is not a valid array", 400);
		// validate student properties
		if (!isset($student["cedula"]) || (empty($student["cedula"]) && !is_numeric($student["cedula"])))
			throw new Error("Cedula is not a valid number", 400);
		if (!isset($student["first_name"]) || (empty($student["first_name"]) && !is_string($student["first_name"])))
			throw new Error("First name is not a valid string", 400);
		if (!isset($student["middle_name"]) || (empty($student["middle_name"]) && !is_string($student["middle_name"])))
			throw new Error("Middle name is not a valid string", 400);
		if (!isset($student["last_name"]) || (empty($student["last_name"]) && !is_string($student["last_name"])))
			throw new Error("Last name is not a valid string", 400);
		if (!isset($student["sur_name"]) || (empty($student["sur_name"]) && !is_string($student["sur_name"])))
			throw new Error("Sur name is not a valid string", 400);
		if (!isset($student["semester"]) || (empty($student["semester"]) && !is_numeric($student["semester"])))
			throw new Error("Semester is not a valid number", 400);
		if (!isset($student["reg_date"]) || (empty($student["reg_date"]) && !is_string($student["reg_date"])))
			throw new Error("Reg date is not a valid string", 400);
		if (!isset($student["career_id"]) || (empty($student["career_id"]) && !is_numeric($student["career_id"])))
			throw new Error("Career is not a valid number", 400);
		if (!isset($student["status"]) || (empty($student["status"]) && !is_numeric($student["status"])))
			throw new Error("Status is not a valid number", 400);
		// map param in a array
		$params = [
			":cedula" => $student["cedula"],
			":first_name" => $student["first_name"],
			":middle_name" => $student["middle_name"],
			":last_name" => $student["last_name"],
			":sur_name" => $student["sur_name"],
			":semester" => $student["semester"],
			":reg_date" => $student["reg_date"],
			":career_id" => $student["career_id"],
			":status" => $student["status"]
		];
		$result = parent::query($query, $params);
		return $result[0]["cedula"];
	}

	function getBystatus($status)
	{
		$query = "SELECT COUNT(*) FROM alumnos WHERE retiro = :status";
		$params = [
			":status" => $status
		];
		$result = parent::query($query, $params);
		return $result[0]["count"];
	}
	// Create por implementar 
	// Delete por implementar
}
