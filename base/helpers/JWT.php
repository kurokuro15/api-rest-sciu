<?php

namespace base\helpers;

use base\helpers\Config;
use base\helpers\Encrypter;
use Error;

class JWT
{
  private $header;
  private $payload;
  private $secret;
  private $tokenParts;

  public function __construct(
    $header = null,
    $payload = null,
    $secret = null
  ) {
    $this->header = $header ?? ["alg" => "HS256", "typ" => "JWT"];
    $this->payload = $this->genPayload($payload ?? []);
    $this->secret = $secret ?? $this->getConfigFileData('pepper')['peperoni'];
    $this->encode();
  }
  
  public function getExpiration() {
    return $this->payload["exp"];
  }

  private function genPayload($data)
  {
    $twoHours = 60 * 60 * 2; // 7.200 seconds
    $iat = time();
    $exp  = $iat + $twoHours;
    $payload = [];

    // set exp time
    $payload['exp'] = $exp;
    $payload['iat'] = $iat;

    // set data
    foreach ($data as $k  => $v) {
      $payload[$k] = $v;
    }

    return $payload;
  }

  private function encode()
  {
    $header = Encrypter::base64UrlEncode(json_encode($this->header));
    $payload = Encrypter::base64UrlEncode(json_encode($this->payload));

    $this->tokenParts = [
      'header' => $header,
      'payload' => $payload
    ];
  }

  public function getSignature()
  {
    $signature = hash_hmac(
      "sha256",
      "{$this->tokenParts['header']}.{$this->tokenParts['payload']}",
      $this->secret
    );

    $this->tokenParts['signature'] = Encrypter::base64UrlEncode($signature);
    return Encrypter::base64UrlEncode($signature);
  }

  public function getToken()
  {
    if (isset($this->tokenParts['signature']))
      return "{$this->tokenParts['header']}.{$this->tokenParts['payload']}.{$this->tokenParts['signature']}";
    else
      throw new Error("Json Web Token not Signature");
  }

  static public function validateJWT($token)
  {
    $tokenParts = JWT::decode($token);

    list($header, $payload, $signature) = $tokenParts;

    $expired = $payload['exp'] - time() < 0;
    if ($expired) {
      throw new Error("The JWT sent is expired.", 403);
    }

    $jwt = new self($header, $payload);

    if ($signature !== $jwt->getSignature()) {
      throw new Error("The JWT sent is not valid. Invalid signature: $signature", 403);
    }
    return true;
  }

  static public function decode($token)
  {
    $tokenParts = explode(".", $token);
    $header = json_decode(Encrypter::base64UrlDecode(($tokenParts[0])), true);
    $payload = json_decode(Encrypter::base64UrlDecode(($tokenParts[1])), true);
    $signature = $tokenParts[2];

    return [$header, $payload, $signature];
  }

  private function getConfigFileData($file)
  {
    $data = file_get_contents(dirname(__FILE__,3) . "/config/" . $file);
    return json_decode($data, true);
  }
}
