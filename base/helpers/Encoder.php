<?php

namespace base\helpers;

class Encoder
{
  static function base64UrlEncode($str)
  {
    return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
  }

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
}
