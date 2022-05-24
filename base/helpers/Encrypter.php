<?php
namespace base\Helpers;
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
		return password_hash($pass, PASSWORD_ARGON2ID);
	}

	private function getFileData($file)
	{
		$dir = dirname(dirname(__FILE__));
		$json = file_get_contents($dir . "\config/" . $file);
		return json_decode($json, true);
	}
}
