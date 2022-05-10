<?php
namespace api\Models;
include(dirname(dirname(__FILE__)) . '/base/models/Model.php');
use base\models\Model;
use ValueError;

class Student extends Model {
/**
 * Retrieve a Student by 'cedula'
 */
	function get($id_cedula){
		//Validate param
		if(!isset($id_cedula) || (int) $id_cedula === 0) {
			throw new ValueError("Cedula not is a number");
		}
		// map param in a array
		$param = [":id_cedula" => $id_cedula];
		// prepare query
		$query = "SELECT id_cedula as cedula, id_carrera as career_id, nombrecarrera as career, nombre1 as first_name, nombre2 as middle_name, apellido1 as last_name, apellido2 as sur_name, semestre as semester, fechainscr as reg_date FROM alumnos JOIN carreras ON id_carrera = id_carrer  WHERE alumnos.id_cedula = :id_cedula;";
		// retrieve data and save in an variable
		$data = parent::query($query,$param);
		if(is_array($data)){
			// we mapping properties of class to use this info. And send Json object form return.
			foreach($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		}
	}
}
$student = new Student;
$student->get(26576198);
var_dump($student->first_name);