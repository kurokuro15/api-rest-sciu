<?php

namespace base\helpers;

class Config
{
  public static function getConfigFileData($file)
  {
    $data = file_get_contents(__ROOT__ . "/config/" . $file);
    return json_decode($data, true);
  }
}
