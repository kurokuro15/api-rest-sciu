<?php

namespace base\helpers;

use base\helpers\Config;
use base\helpers\Encoder;
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
    $this->secret = $secret ?? Config::getConfigFileData('pepper')['peperoni'];
    $this->encode();
  }

  private function genPayload($data)
  {
    $twoHours = 60 * 60; // 7.200 seconds
    $exp  = time() + $twoHours;
    $payload =  [];

    // set exp time
    $payload['exp'] = $exp;

    // set data
    foreach ($data as $k  => $v) {
      $payload[$k] = $v;
    }

    return $payload;
  }

  private function encode()
  {
    $header = Encoder::base64UrlEncode(json_encode($this->header));
    $payload = Encoder::base64UrlEncode(json_encode($this->payload));

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
      $this->secret,
      true
    );

    return Encoder::base64UrlEncode($signature);
  }

  public function getToken()
  {
    return "{$this->tokenParts['header']}.{$this->tokenParts['payload']}.{$this->getSignature()}";
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
      print_r($signature);

      echo "\n\n";

      print_r($jwt->getSignature());
      throw new Error("The JWT sent is not valid. Invalid signature: $signature", 403);
    }

    return true;
  }

  static public function decode($token)
  {
    $tokenParts = explode(".", $token);
    $header = json_decode(Encoder::base64UrlDecode(($tokenParts[0])), true);
    $payload = json_decode(Encoder::base64UrlDecode(($tokenParts[1])), true);
    $signature = $tokenParts[2];

    return [$header, $payload, $signature];
  }
}
