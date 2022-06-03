<?php

namespace api\Models;

use base\models\Model;
use Error;
use ValueError;

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
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new ValueError("Cedula is not a valid number", 400);
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
			fechainscr AS reg_date
		FROM
			alumnos
		JOIN carreras ON
			id_carrera = id_carrer
		WHERE
			alumnos.id_cedula = :cedula;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (is_array($data)) {
			// Map properties of class to use this info. And return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
			// if all it's okay return the student.
			return $data[0];
		} else {
			throw new Error("Not Found", 404);
		}
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

		if ($params["offset"] && $params["limit"]) {
			$pagination = parent::pagination($params);
			$query .= " OFFSET :offset LIMIT :limit";
		}
		$data = parent::query($query, $pagination);
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}
}
