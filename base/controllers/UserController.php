<?php

namespace base\controllers;

use base\helpers\Encrypter;
use base\models\User;
use Error;
use Throwable;

class UserController extends Controller
{
	protected $users;
	protected $encrypter;
	function __construct()
	{
		parent::__construct();
		$this->users = new User;
		$this->encrypter = new Encrypter;
	}
	/**
	 * Manejador de peticiones GET para un solo usuario
	 */
	function retrieve($params)
	{
		// Validate param
		if (!isset($params["username"])) {
			throw new Error("username not ¿declared?", 400);
		}
		$username = $params["username"];

		try {
			// validate that $username is an string or an integer
			if (!is_string($username) && !is_int($username)) {
				throw new Error("username not ¿declared?", 400);
			}
			// if $username is an integer, use users->get()
			if (is_int($username)) {
				$user = $this->users->get($username);
			} else {
				// if $username is an string, use users->getUser()
				$user = $this->users->getUser($username);
			}
			// validate that $user is an array
			if (!is_array($user)) {
				throw new Error("Not Found", 404);
			}
			//send response with user
			$this->response->send(["users" => $user]);
		} catch (Throwable $err) {
			$this->response->send($err->getMessage(), $err->getCode());
		}
	}
	/**
	 * Manejador de peticiones GET para todos los usuarios
	 */
	function get($params)
	{
		try {
			$params = array_merge($params, $this->request->get());
			list($data, $meta) = $this->users->getAll($params);

			parent::getMeta($meta);

			$this->response->send(["users" => $data]);
		} catch (Throwable $err) {
			$this->response->send($err->getMessage(), $err->getCode());
		}
	}
	/**
	 * Manejador de post para crear usuarios
	 */
	function createUser($params)
	{
		$user = $this->request->input();
		// Validamos que el usuario no exista
		$username = $user['username'];

		try {
			$userExists = $this->users->getUser($username);
		} catch (Error $err) {
			$userExists = null;
		}

		if ($userExists) {
			throw new Error("El usuario ya existe", 400);
		}
		//validamos que no vengan los campos requeridos vacíos
		if (
			empty($user['username']) 			||
			empty($user['password']) 			||
			empty($user['question']) 			||
			empty($user['answer']) 				||
			empty($user['question_two']) 	||
			empty($user['answer_two']) 		||
			empty($user['question_three']) ||
			empty($user['answer_three'])
		) {
			throw new Error("Faltan campos requeridos", 403);
		}
		//encriptamos la contraseña y las respuestas.
		$user['password'] = $this->encrypter->passEncrypt($user['password']);
		$user['answer'] = $this->encrypter->passEncrypt($user['answer']);
		$user['answer_two'] = $this->encrypter->passEncrypt($user['answer_two']);
		$user['answer_three'] = $this->encrypter->passEncrypt($user['answer_three']);

		//creamos el usuario
		$user = $this->users->create($user);
		//respondemos con el usuario creado
		$this->response->send(["users" => ["id" => $user]]);
	}
	/**
	 * Manejador de post para actualizar contraseña de usuarios
	 */
	function updatePassword($params)
	{
		$input = $this->request->input();
		//validamos que no vengan los campos requeridos vacíos
		if (
			empty($input['username']) 			||
			empty($input['new_password']) 	||
			empty($input['answer']) 				||
			empty($input['answer_two']) 		||
			empty($input['answer_three'])
		)
			throw new Error("Faltan campos requeridos", 403);

		// Validamos que el usuario exista
		$username = $input['username'];
		$this->users->getUser($username);
		$users = $this->users->getAnswers($this->users->id);
		if (!$this->users->getUser($username)) {
			throw new Error("El usuario no existe", 400);
		}

		//encriptamos las respuestas
		$input['answer'] = $this->encrypter->passEncrypt($input['answer']);
		$input['answer_two'] = $this->encrypter->passEncrypt($input['answer_two']);
		$input['answer_three'] = $this->encrypter->passEncrypt($input['answer_three']);

		//validamos las respuestas con el users
		//Tengo que utilizar auth para validar el match password
		if (
			$users["answer"] != $input['answer'] ||
			$users["answer_two"] != $input['answer_two'] ||
			$users["answer_three"] != $input['answer_three']
		)
			throw new Error("Las respuestas no coinciden", 403);
		// encriptamos la contraseña y traemos el id
		$input['new_password'] = $this->encrypter->passEncrypt($input['new_password']);
		
		//Tengo que utilizar auth para validar el match password
		if($users->password === $input['new_password'])
			throw new Error("La contraseña no puede ser la misma", 403);

		$input['id'] = $this->users->id;

		//actualizamos la contraseña
		$id = $this->users->updatePassword($input);
		$this->response->send(["users" => ["id" => $id]]);
	}
	/**
	 * Manejador de DELETE para desactivar el usuario
	 */
	function delete($params)
	{
		try {
			// Validate param
			if (!isset($params["id"]) && !is_int($params["id"])) {
				throw new Error("id not ¿declared?", 400);
			}
			$user = $this->users->get($params["id"]);

			$data = $this->users->deleteUser($user);
			$this->response->send(["users" => $data]);
		} catch (Throwable $err) {
			$this->response->send($err->getMessage(), $err->getCode());
		}
	}
}
