<?php
define('__ROOT__', dirname(__FILE__));

require_once __ROOT__ . '/Models/Model.php';

$_model = new Model;
$id = 26576198;
$res = $_model->query("SELECT id_cedula, nombre1, factura, SUM(pagos.monto) as monto FROM alumnos 
JOIN emisiones ON id_cedula = id_cedul JOIN pagos ON idregistro = idregistr 
WHERE id_cedula = $id 
GROUP BY id_cedula, nombre1, factura;");
print_r($res);
