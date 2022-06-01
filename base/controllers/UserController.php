<?php

namespace base\controllers;

use base\helpers\Encrypter;
use base\models\User;
use Error;

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
}
