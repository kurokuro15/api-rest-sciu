<?php

namespace base\models;

use base\helpers\Encrypter;
use Error;
use PDOException;

class User extends Model
{
	//get one by id
	public function get($id)
	{
		//Validate param
		if (!isset($id) || (empty($id) && !is_numeric($id))) {
			throw new Error("id not ¿declared?", 400);
		}
		// map param in a array
		$param = [":id" => $id];

		$query = "SELECT
			u.id,
			u.username,
			u.password,
			us.status,
			u.secret,
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
			u.id = :id;";

		// retrieve data and save in an variable
		$data = parent::queryAuth($query, $param);

		//validate data
		if (count($data)  <= 0)
			throw new Error("Not Found", 404);

		// Map properties of class to use this info. And return object.
		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		}
		return $data[0];
	}
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
			u.id,
			u.username,
			u.password,
			us.status,
			u.secret,
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
		if (count($data)  <= 0)
			throw new Error("Not Found", 404);

		// Map properties of class to use this info. And return object.
		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		}
		return $data[0];
	}
	//get all
	public function getAll($params)
	{
		$query = "SELECT
			u.id,
			u.username,
			us.status,
			r.rol
		FROM
			app_user u
		JOIN user_status us ON
			us.id = u.status
		JOIN user_rol ur ON
			ur.user = u.id
		JOIN rol r ON
			ur.rol = r.id";

		//Add pagination to query
		list($interval, $placeholder, $meta) = parent::pagination($params, false);
		$params = array_merge($params, $interval);

		// Obtenemos el total de elementos de la query y lo guardamos en meta
		$meta["count"] = $this->countAuth($query);

		// añadimos el placeholder de paginación		
		$query .= $placeholder;
		$data = parent::queryAuth($query, $params);

		//validate data
		if (count($data)  <= 0)
			throw new Error("Not Found", 404);

		return [$data, $meta];
	}
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
			$param = [":rol" => 1];
			if (!empty($user["rol"])) {
				$param[":rol"] = $user["rol"];
			}
			$data = parent::queryAuth($queryThree, $param);
			$this->authentication->commit();

			//validate data
			if (count($data)  <= 0)
				throw new Error("data not found", 404);

			return $data[0];
		} catch (PDOException $err) {
			$this->authentication->rollBack();
			throw new Error($err->getMessage(), 500);
		}
	}
	//Update (cambiamos la contraseña nada más)
	public function updatePassword($user)
	{
		$query = "UPDATE app_user 
		SET password = :password 
		WHERE username = :username 
		AND id = :id RETURNING id";
		$required = ["username", "password", "id"];

		foreach ($required as $key => $value) {
			if (!isset($user[$value])) {
				throw new Error("$value not ¿declared?", 400);
			}
		}

		$params = [
			":password" => $user["password"],
			":username" => $user["username"],
			":id" => $user["id"]
		];

		$data = parent::queryAuth($query, $params);
		return $data;
	}

	//Delete (cambiamos el estatus a inactivo)
	public function deleteUser($user)
	{
		$query = "UPDATE app_user 
		SET status = 0 
		WHERE username = :username 
		AND id = :id RETURNING id";
		$required = ["username", "id"];

		foreach ($required as $key => $value) {
			if (!isset($user[$value])) {
				throw new Error("$value not ¿declared?", 400);
			}
		}

		$params = [
			":username" => $user["username"],
			":id" => $user["id"]
		];

		$data = parent::queryAuth($query, $params);
		return $data;
	}
	/**
	 * get secret answers by user
	 */
	public function getAnswers($id)
	{
		$query = "SELECT
			u.id,
			u.username,
			u.password,
			s.answer,
			s.answer_two,
			s.answer_three
		FROM
			app_user u
		JOIN secret s ON
			u.secret = s.id
		WHERE
			u.id = :id";
		$params = [
			":id" => $id
		];
		$data = parent::queryAuth($query, $params);
		return $data[0];
	}
	/**
	 * get secret questions by user
	 */
	public function getQuestions($id)
	{
			$query = "SELECT
			u.id,
			u.username,
			s.question,
			s.question_two,
			s.question_three
		FROM
			app_user u
		JOIN secret s ON
			u.secret = s.id
		WHERE
			u.id = :id";
		$params = [
			":id" => $id
		];
		$data = parent::queryAuth($query, $params);
		return $data[0];
	}
}
