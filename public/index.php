<?php
namespace api;
/**
 * Este index será la ruta principal que servirá la API.
 */
spl_autoload_register('api\autoload');
use api\controllers\FacturaController;

function autoload($clase,$dir=null){

    //Directorio raíz de mi proyecto (ruta absoluta)
    if (is_null($dir)){
			$dirname = str_replace('\public', '', dirname(__FILE__));
			$dir = realpath($dirname);
    }

    //Escaneo en busca de clases de forma recursiva
    foreach (scandir($dir) as $file){
        //Si es un directorio (y no es de sistema) busco la clase dentro de él
        if (is_dir($dir."/".$file) AND substr($file, 0, 1) != "."){
            autoload($clase, $dir."/".$file);
        }
        //Si es archivo y el nombre coincide con la clase que quiero instanciar
        else if (is_file($dir."/".$file) AND $file == substr(strrchr($clase, "\\"), 1).".php"){
            require($dir."/".$file);
        }
    }
}
// instancio el controller
$factura = new FacturaController;
//Ruta de la home
$home = "/api/public/";

//Quito la home de la ruta de la barra de direcciones
$ruta = str_replace($home, "", $_SERVER["REQUEST_URI"]);

//Creo el array de ruta (filtrando los vacíos)
$array_ruta = array_filter(explode("/", $ruta));
foreach ($array_ruta as $i => $section){
	if(str_contains($section,'?'))
		$array_ruta[] = array_filter(explode("?", $section))[0];
		$id = array_filter(explode("?id=", $section));
}
//Decido la ruta en función de los elementos del array para factura.
if (isset($array_ruta[3]) && $array_ruta[3] == "factura"){
	//Llamo al método ver pasándole la clave que me están pidiendo
	$factura->index($id[1]);
}
else{

	//Llamo al método por defecto del controlador
}
?>