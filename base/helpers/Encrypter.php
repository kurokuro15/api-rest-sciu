<?php

namespace base\helpers;

/**
 * Clase para cifrar cadenas de texto
 */
class Encrypter
{
	protected $secret;

	function __construct($secret = null)
	{
		$this->secret = $secret || $this->getFileData("pepper")['peperoni'];
	}

	/**
	 * Create SHA256 Hash With include secret
	 */
	public function encrypt($str)
	{
		return hash_hmac("sha256", $str, $this->secret);
	}

	/**
	 * Create password hash with ARGON2ID
	 */
	public function passEncrypt($str)
	{
		$pass = $this->encrypt($str);
		return password_hash($pass, PASSWORD_BCRYPT);
	}

	/**
	 * Static method to Encode base64Url
	 */
	static function base64UrlEncode($str)
	{
		return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
	}

	/**
	 * Static method to Decode base64Url
	 */
	static public function base64UrlDecode($str)
	{
		return
			base64_decode(
				str_pad(
					strtr($str, "-_", "+/"),
					strlen($str) % 4,
					"=",
					STR_PAD_RIGHT
				)
			);
	}

	private function getFileData($file)
	{
		$dir = dirname(__FILE__,3);
		$json = file_get_contents($dir . "\config/" . $file);
		return json_decode($json, true);
	}
}
