<?php
require_once 'Models/Model.php';

$conection = new Model;
$id = 26576198;
$res = $conection->query("SELECT id_cedula as 'cedula', nombre1 as 'nombre', factura, SUM(pagos.monto) as monto FROM alumnos 
JOIN emisiones ON id_cedula = id_cedul JOIN pagos ON idregistro = idregistr 
WHERE id_cedula = $id 
GROUP BY id_cedula, nombre1, factura;");

print_r($res);