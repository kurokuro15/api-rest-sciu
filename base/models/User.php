<?php

namespace base\models;

use Error;
use ValueError;

class User extends Model
{
	//get one by id
	//get one by user
	public function getUser($username)
	{
		//Validate param
		if (!isset($username)) {
			throw new ValueError("username not ¿declared?", 400);
		}
		// map param in a array
		$param = [":username" => $username];

		$query = "SELECT
			u.username,
			u.password,
			us.status,
			r.rol
		FROM
			app_user u
		JOIN user_status us ON
			us.id = u.status
		JOIN user_rol ur ON
			ur.user = u.id
		JOIN rol r ON
			ur.rol = r.id	
		WHERE
			u.username = :username;";

		// retrieve data and save in an variable
		$data = parent::queryAuth($query, $param);
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
	//get all
	//create
	//update
	//delete
}
