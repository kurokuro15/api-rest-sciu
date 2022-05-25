<?php

namespace base\middleware;

use \base\controllers\Controller;
use \base\models\Authentication;
use \base\models\User;
use \Error;
/**
 * Middleware de autenticación
 */
class AuthenticationMiddleware extends Controller
{
	protected $auth;
	protected $users;
	function __construct()
	{
		parent::__construct();
		$this->auth = new Authentication;
		$this->users = new User;
	}
	/**
	 * Controlador para autenticar pase de usaurio y contraseña
	 */
	function authUser($params)
	{
		$credentials = $this->request->input();
		// recibo usuario y contraseña... Valido que existan
		if (empty($credentials['username']) || empty($credentials['password'])) {
			throw new Error("Usuario o contraseña no enviados", 403);
		}
		// mapeamos ambas propiedades
		$username = $credentials['username'];
		$password = $credentials['password'];
		
		// Buscamos al usuario en la db y devolvemos id, user y contraseña
		try {

			$user = $this->users->getUser($username);
		} catch (Error $err) {
			throw new Error("Contraseña o usuario inválidos", 403);
		}
		// Si no existe, esto devolverá un error 404 not user found
		$passValidated = $this->auth->matchPassword($password,$user['password']);
		
		if(!$passValidated) {
			throw new Error("Contraseña o usuario inválidos", 403);
		}

		$token = $this->auth->generateToken($user);

		if(empty($token)) {
			throw new Error("Fatal error interno",500);
		}

		//debe ser un objeto tipo ["token"=> "token", "expiration" => "time_stamp"]
		$this->response->send(["token" => $token]);
	}

	/**
	 * Controlador para autenticar pase de preguntas de recuperación de contraseña...
	 */
	public function authQuestions($params){

	}
	/**
	 * Controlador para validar que el usuario está autenticado
	 */
	public function authy($params, $next) { 

	}
}
