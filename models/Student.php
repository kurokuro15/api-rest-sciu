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
	// Create por implementar 
	// Delete por implementar
}
