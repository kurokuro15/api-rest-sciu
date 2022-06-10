<?php

namespace base\models;

use \base\helpers\Encrypter;
use \base\helpers\JWT;
use \base\models\Model;
use Error;
use PDO;
use PDOException;

/**
 * Class Model of Authentication middleware
 */
class Authentication extends Model
{
	private $encrypter;
	private $jwt;
	function __construct()
	{
		parent::__construct();
		$this->encrypter = new Encrypter;
	}

	/**
	 * Validate password
	 */
	public function matchPassword($password, $validPassword)
	{
		$encryptedPass = $this->encrypter->encrypt($password);
		return password_verify($encryptedPass, $validPassword);
	}

	/**
	 * Generar un token 
	 */
	public function generateToken($user)
	{

		$payload["sub"] = $user["username"];
		$payload["rol"] = $user["rol"];

		$this->jwt = new JWT(null, $payload);
		$this->jwt->getSignature();
		return [$this->jwt->getToken(), $this->jwt];
	}
	function encrypt($string){
		return $this->encrypter->passEncrypt($string);
	}
	// Obtener un token por el token (usuario asociado al token y el token persé)
	// Crear un token para el usuario pasado
	// Actualizar un token, vencerlo. 

}
