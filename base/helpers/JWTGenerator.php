<?php

namespace base\Helpers;

use Error;

/**
 * Generator of Json Web Tokens HS265
 */
class JWTGenerator
{

	private $data;
	private $header;
	private $jwt;
	private $playload;
	private $secret;
	private $signature;

	function __construct($header = null, $data = null, $secret = null)
	{
		$this->data = $data;
		$this->header = $this->base64url_encode($header || json_encode(["alg" => "HS256", "typ" => "JWT"]));
		$this->secret = $secret || $this->getFileData("pepper")['peperoni'];

		// generate token automatic with instance 
		if ($this->data) {
			$this->data = JWT::generatePlayload($this->data);
			$this->generateJWT();
		}
	}

	function getJWT()
	{
		return $this->jwt;
	}

	/**
	 * Generate Json Web Token with data parameter and optional secret
	 */
	private function generateJWT($data = null, $secret = null)
	{
		// codifica el contenido del token de un array map a un json string
		$data = $data || $this->data;
		$this->playload = $this->base64url_encode(json_encode($data));

		// genera la firma
		$this->signatureEncode($secret);

		$this->jwt = new JWT($this->header, $this->playload, $this->signature);

		return "{$this->header}.{$this->playload}.{$this->signature}";
	}

	/**
	 * Validate Json Web Token 
	 */
	function validateJWT($jwt)
	{
		// divide el contenido del token
		$tokenParts = explode(".", $jwt);
		$header = $tokenParts[0];
		// decodificamos el json string a un array map
		$playload = json_decode($this->base64url_decode($tokenParts[1]));
		$signature = $tokenParts[2];

		// revisa la fecha de vencimiento del token
		$time = $playload->iat;
		$expired = $time - time() < 0;
		if ($expired) {
			throw new Error("The JWT send it is Expired.", 403);
		}

		// generar firma en base a la cabecera y data del token con la clave secreta
		$this->generateJWT($playload);

		// validate $header provide is equal to set header
		if ($header !== $this->header) {
			throw new Error("The JWT send it is not valid. Failed header: $header", 403);
		}
		if ($signature !== $this->signature) {
			throw new Error("The JWT send it is not valid. Failed Signature: $signature", 403);
		}

		return true;
	}

	/**
	 * signature with base64url encode
	 */
	private function signatureEncode($secret = null)
	{
		$secret = $secret || $this->secret;
		//HS265 encode
		$unsignature = hash_hmac("sha256", "{$this->header}.{$this->playload}", $secret, true);

		$this->signature = $this->base64url_encode($unsignature);
	}
	/** encode strings base64url */
	private function base64url_encode($string)
	{
		return rtrim(strtr(base64_encode($string), "+/", "-_"), "=");
	}
	/** decode strings base64url */
	private function base64url_decode($string)
	{
		return base64_decode(
			str_pad(
				strtr($string, "-_", "+/"),
				strlen($string) % 4,
				"=",
				STR_PAD_RIGHT,
			),
		);
	}
	/**Get data of a file */
	private function getFileData($file)
	{
		$dir = dirname(dirname(__FILE__));
		$json = file_get_contents($dir . "\config/" . $file);
		return json_decode($json, true);
	}
}

/** pseudo Model of JWT */
class JWT
{

	private $header;
	private $playload;
	private $signature;
	private $token;
	/** encoded jwt */
	function __construct($header, $playload, $signature)
	{
		$this->header = $header;
		$this->playload = $playload;
		$this->signature = $signature;
		$this->token = "{$this->header} . {$this->playload} . {$this->signature}";
	}
	public function get()
	{
		return $this->token;
	}
	public function body()
	{
		return $this->playload;
	}

	public function signature()
	{
		return $this->signature;
	}

	static function generatePlayload($data = [])
	{
		$aDay = 60 * 60 * 24; //86.400 seconds
		$iat  = time() + $aDay;
		$playload =  [];
		//set time
		$playload['iat'] = $iat;

		//set data
		foreach ($data as $k  => $v) {
			$playload[$k] = $v;
		}
		return $playload;
	}
}
