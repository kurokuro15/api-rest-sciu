<?php

namespace base\helpers;
// setea por defecto la zona horaria de America/Caracas. Técnicamente -4:00 GMT 
date_default_timezone_set('America/Caracas');
spl_autoload_register('\base\Helpers\autoload');
function autoload($clase, $dir = null)
{
    //Directorio raíz de mi proyecto (ruta absoluta)
    if (is_null($dir)) {
        $dirname = dirname(__FILE__, 3);
        $dir = realpath($dirname);
    }

    //Escaneo en busca de clases de forma recursiva
    foreach (scandir($dir) as $file) {
        //Si es un directorio (y no es de sistema) busco la clase dentro de él
        if (is_dir($dir . "/" . $file) and substr($file, 0, 1) != ".") {
            \base\helpers\autoload($clase, $dir . "/" . $file);
        }
        //Si es archivo y el nombre coincide con la clase que quiero instanciar
        else if (is_file($dir . "/" . $file) and $file == substr(strrchr($clase, "\\"), 1) . ".php") {
            require($dir . "/" . $file);
        }
    }
}
