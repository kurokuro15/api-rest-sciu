<?php

namespace base\models;

use base\helpers\Encrypter;
use Error;

class User extends Model
{
	//get one by id
	//get one by user
	public function getUser($username)
	{
		//Validate param
		if (!isset($username)) {
			throw new Error("username not ¿declared?", 400);
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
	public function create($user)
	{
		//Validate param
		if (!isset($user)) {
			throw new Error("user not ¿declared?", 400);
		}


		$queryOne  = "insert into secret(question, answer, question_two, answer_two, question_three, answer_three) values(:question, :answer, :question_two, :answer_two, :question_three, :answer_three);";
		$queryTwo = "insert into app_user (username, password, status, secret) values (:username, :password, :status, (select currval(pg_get_serial_sequence('secret', 'id')) ) );";
		$queryThree = "insert into user_rol (\"user\", rol) values ( (select currval(pg_get_serial_sequence('app_user', 'id')) ), :rol) returning \"user\"";
		// retrieve data and save in an variable
		//prepare PDOtransaction
		$this->authentication->beginTransaction();
		try {
			//execute secret query
			$param = [
				":question" => $user["question"],
				":answer" => $user["answer"],
				":question_two" => $user["question_two"],
				":answer_two" => $user["answer_two"],
				":question_three" => $user["question_three"],
				":answer_three" => $user["answer_three"],
			];
			parent::queryAuth($queryOne, $param);

			//execute app_user query
			$param = [
				":username" => $user["username"],
				":password" => $user["password"]
			];
			if (empty($user["status"])) {
				$param[":status"] = 1;
			} else {
				$param[":status"] = $user["status"];
			}
			parent::queryAuth($queryTwo, $param);

			//execute user_rol query
			if (empty($user["rol"])) {
				$param[":rol"] = 1;
			} else {
				$param[":rol"] = $user["rol"];
			}
			$data = parent::queryAuth($queryThree, $param);
			$this->authentication->commit();

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
		} catch (\Exception $e) {
			$this->authentication->rollBack();
			throw new Error("Error", 500);
		}
	}
	//update
	//delete
}
