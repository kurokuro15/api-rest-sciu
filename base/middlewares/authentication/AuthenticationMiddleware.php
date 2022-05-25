<?php

namespace base\middleware;

use \base\controllers\Controller;
use base\helpers\JWT;
use \base\models\Authentication;
use \base\models\User;
use DatePeriod;
use DateTime;
use \Error;
use Throwable;

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

		// Validamos si es un usuario inactivo o eliminado.
		$inactive = 0;
		if ($user["status"] === $inactive) {
			throw new Error("Usuario inactivo o eliminado recientemente", 403);
		}

		$passValidated = $this->auth->matchPassword($password, $user['password']);
		if (!$passValidated) {
			throw new Error("Contraseña o usuario inválidos", 403);
		}
		// devuelve un array donde, la posición 0 es el token string y la posición 1 es el objeto JWT instanciado.
		$token = $this->auth->generateToken($user);
		if (empty($token)) {
			throw new Error("Fatal error interno", 500);
		}

		//	seteamos la fecha de expiración fuera del token para facilitar el manejo del mismo al front
		$expiration = date("Y-m-d H:i:s", $token[1]->getExpiration());

		//Respondemos con un objeto tipo ["token"=> "token", "expiration" => "time_stamp"]
		$this->response->send(["token" => $token[0], "expiration" => $expiration]);
	}

	/**
	 * Controlador para autenticar pase de preguntas de recuperación de contraseña...
	 */
	public function authQuestions($params)
	{
	}
	/**
	 * Controlador para validar que el usuario está autenticado
	 */
	public function authy($params, $next)
	{
		// validamos que exista el encabezado de autorización
		if (empty($this->request->headers['Authorization'])) {
			throw new Error("Non Authorized. need a valid token", 403);
		}
		// si existe el encabezado, lo extraemos y le retiramos la palabra 'Bearer'
		$token = $this->request->headers['Authorization'];
		$jwt = preg_replace("/Bearer /", '', $token);
		// validamos el JWT que debe venir
		try {
			$authorized = JWT::validateJWT($jwt);
			if ($authorized) {
				$now = time();
				$fiveMinutes = 60 * 5;
				// verificamos si está por vencerse el JWT, de hacerlo, le generamos uno nuevo.
				$uncryptedJWT = JWT::decode($jwt);
				if ($uncryptedJWT[1]['exp'] - $now <= $fiveMinutes) {
					// preparamos un nuevo JWT con el payload recibido.
					$payload = $uncryptedJWT[1];
					unset($payload['exp']);
					unset($payload['iat']);
					$newJWT = new JWT(null, $payload);
					$expiration = $newJWT->getExpiration();
					$newJWT->getSignature();
					$token = $newJWT->getToken();
					$this->response->send(["token" => $token, "expiration" => $expiration]);
				}
				if (is_callable($next)) {
					call_user_func($next, $params);
				} else {
					throw new Error("Fatal Error, not calleable function", 500);
				}
			} else {
				throw new Error("Non Authorized. need a valid token", 403);
			}
		} catch (Error $err) {
			// mandamos un error para redirigir al login.
			throw new Error("Session expired", 302);
		}
	}
}
