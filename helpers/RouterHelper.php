<?php
namespace api\Helper;

class RouterHelper
{
    public function vista($vista,$datos){

        $archivo = "../routers/$vista.php";
        require($archivo);

    }
}