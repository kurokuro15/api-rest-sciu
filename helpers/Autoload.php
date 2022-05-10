<?php
namespace api\Helpers;
spl_autoload_register('\api\Helpers\autoload');
function autoload($clase, $dir = null) {
        //Directorio raíz de mi proyecto (ruta absoluta)
    if (is_null($dir)) {
        $dirname = str_replace('\helpers', '', dirname(__FILE__));
        $dir = realpath($dirname);
    }

    //Escaneo en busca de clases de forma recursiva
    foreach (scandir($dir) as $file) {
        //Si es un directorio (y no es de sistema) busco la clase dentro de él
        if (is_dir($dir . "\\" . $file) and substr($file, 0, 1) != ".") {
            \api\helpers\autoload($clase, $dir . "/" . $file);
        }
        //Si es archivo y el nombre coincide con la clase que quiero instanciar
        else if (is_file($dir . "/" . $file) and $file == substr(strrchr($clase, "\\"), 1) . ".php") {
            require($dir . "\\" . $file);
        }
    }
}