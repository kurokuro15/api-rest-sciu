<?php

namespace api\Models;
//include para probar no más xD 
use base\models\Model;
use Error;
use ValueError;

class Student extends Model
{
	/**
	 * Retrieve a Student by 'cedula'
	 */
	function get($cedula)
	{
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new ValueError("Cedula not is a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];
		// prepare query
		$query = "SELECT id_cedula as cedula, id_carrera as career_id, nombrecarrera as career, nombre1 as first_name, nombre2 as middle_name, apellido1 as last_name, apellido2 as sur_name, semestre as semester, fechainscr as reg_date FROM alumnos JOIN carreras ON id_carrera = id_carrer  WHERE alumnos.id_cedula = :cedula;";
		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		if (is_array($data)) {
			// we mapping properties of class to use this info. And send Json object form return.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data[0];
	}

	function getAll($page = 0, $items = 5){
		$pagination = parent::pagination($page,$items);

		$query = "SELECT cedula as cedula, nombre1 as first_name, apellido1 as last_name, semestre as semester, fechainscr as reg_date, nombrecarrera as career FROM alumnos JOIN carreras ON id_carrera = id_carrer  ORDER BY reg_date DESC LIMIT :limit OFFSET :offset";
		$data = parent::query($query,$pagination);
		if (count($data)<1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}
}
