<?php

namespace base\middleware;

use \base\controllers\Controller;
use base\helpers\JWT;
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
	 * Controlador para autenticar pase de usuario y contraseña
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
		$inactive = "inactivo";
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
	 * Controlador para autenticar respuestas de recuperación de contraseña...
	 */
	public function authQuestions($params)
	{
		try {
			$input = $this->request->input();

			// recibo usuario y estado de la solicitud: 
			// 1 = solicitud de recuperación de contraseña
			// 2 = solicitud de cambio de contraseña
			if (empty($input['username']) || empty($input['status'])) {
				throw new Error("Usuario o estado de solicitud no enviados", 403);
			}
			$username = $input['username'];
			$status = $input['status'];
			// Buscamos al usuario en la db y devolvemos id, user y contraseña
			$user = $this->users->getUser($username);
			if (empty($user)) {
				throw new Error("Usuario no encontrado", 403);
			}
			// Validamos si es un usuario inactivo o eliminado.
			$inactive = "inactivo";
			if ($user["status"] === $inactive) {
				throw new Error("Usuario inactivo o eliminado recientemente", 403);
			}
			// vemos si el status es 1, vamos a buscar las preguntas y devolverlas. Si el status es 2, tratamos cambiar pass
			if ($status == 1) {
				$questions = $this->users->getQuestions($user['id']);
				if (empty($questions)) {
					throw new Error("No hay preguntas para este usuario", 403);
				}
				$this->response->send(["questions" => $questions]);
			} else if ($status == 2) {
				// recibo usuario y contraseña... Valido que existan
				if (empty($input['username']) || empty($input['new_password']) || empty($input['answer']) || empty($input['answer_two']) || empty($input['answer_three'])) {
					throw new Error("Usuario o contraseña no enviados", 403);
				}
				// Buscamos al usuario y luego las respuestas de preguntas de recuperación de contraseña
				$credentials = [
					'username' => $input['username'],
					'password' => $this->auth->encrypt($input['new_password']),
					'answer' => $input['answer'],
					'answer_two' => $input['answer_two'],
					'answer_three' => $input['answer_three'],
				];
				$credentials["id"] = $user["id"];
				$user = $this->users->getAnswers(($user['id']));

				// validamos las respuestas
				$valid[] = $this->auth->matchPassword($credentials['answer'], $user['answer']);
				$valid[] = $this->auth->matchPassword($credentials['answer_two'], $user['answer_two']);
				$valid[] = $this->auth->matchPassword($credentials['answer_three'], $user['answer_three']);
				if ($valid[0] && $valid[1] && $valid[2]) {
					// cambiamos la contraseña	
					$id = $this->users->updatePassword($credentials);
					$this->response->send(["users" => $id]);
				} else {
					throw new Error("Respuestas inválidas ", 403);
				}
			} else {
				throw new Error("Estado de solicitud inválido", 403);
			}
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
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
				if (is_callable($next)) {
					call_user_func($next, $params);
				} else if (isset($next)) {
					throw new Error("Fatal Error, not calleable function", 500);
				}
			} else {
				throw new Error("Non Authorized. need a valid token", 403);
			}
		} catch (Error $err) {
			// mandamos un error para redirigir al login.
			$error = $err->getMessage() ?: "Session expired";
			$code = $err->getCode() ?: 302;
			throw new Error($error, $code);
		}
	}
	/**
	 * refrescamos el token
	 */
	public function refreshToken($params)
	{
		// validamos que exista el encabezado de autorización
		if (empty($this->request->headers['Authorization'])) {
			throw new Error("Non Authorized. need a valid token", 403);
		}
		// si existe el encabezado, lo extraemos y le retiramos la palabra 'Bearer'
		$token = $this->request->headers['Authorization'];
		$jwt = preg_replace("/Bearer /", '', $token);
		$now = time();
		$greatTime = 60 * 5;
		// verificamos si está por vencerse el JWT, de hacerlo, le generamos uno nuevo.
		$uncryptedJWT = JWT::decode($jwt);
		if ($uncryptedJWT[1]['exp'] - $now <= $greatTime) {
			// preparamos un nuevo JWT con el payload recibido.
			$payload = $uncryptedJWT[1];
			unset($payload['exp']);
			unset($payload['iat']);
			$newJWT = new JWT(null, $payload);
			$expiration = $newJWT->getExpiration();
			$newJWT->getSignature();
			$token = $newJWT->getToken();
		} else {
			$token = $jwt;
			$expiration = date("Y-m-d H:i:s", $uncryptedJWT[1]['exp']);
		}
		$this->response->send(["token" => $token, "expiration" => $expiration]);
	}
}
