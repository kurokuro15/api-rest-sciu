<?php

namespace api\Models;

use \base\Models\Model;

// require dirname(dirname(__FILE__)) . '/base/Models/Model.php';
/**
 * Class Model of collection orders. 
 */
class Order extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve from Order or 'emisiones' table.
	 */
	function get($registro)
	{
		//Validate param
		if (!isset($registro) || (int) $registro === 0) {
			throw new \ValueError("collection order number is not a valid number", 401);
		}
		// map param in a array
		$param = [":registro" => $registro];
		// prepare query
		// Query retrieve the order detail by this id.
		$query = "SELECT idregistro as id, fechaemision as reg_date, concepto as concept, monto as amount,
				id_cedul as cedula, unidades, nombrecategoria as category
				FROM emisiones LEFT JOIN categorias ON idcategori = idcategoria
				WHERE idregistro = :registro ORDER BY reg_date desc, id desc;";
		$data = parent::query($query, $param);
		if (is_array($data)) {
			// we map properties of class to use this info. And send Json object form return.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new \Error("data not found", 404);
		}
		// if all it's okay return the order.
		return $data[0];
	}

	function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new \ValueError("Cedula number is not a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];
		// prepare query
		// query para traer todas las ordenes de cobro pendientes. 
		$query = "SELECT
		id_cedul AS cedula,
		idcategori AS id_category,
		claveconcepto AS username,
		fechaemision AS reg_date,
		concepto AS concept,
		idregistro AS id,
		monto - SUM(pago) AS outstanding,
		idproduct AS id_product,
		unidades AS units
	FROM
		(
		SELECT
			id_cedul,
			idcategori,
			Emisiones.claveconcepto,
			Emisiones.concepto,
			emisiones.fechaemision,
			Emisiones.idregistro,
			anulado,
			emisiones.monto,
			CASE
				WHEN pagos.monto IS NULL THEN 0
				ELSE pagos.monto *(CASE
					WHEN anulado then 0
					ELSE 1
				END)
			END AS pago,
			emisiones.idproduct,
			emisiones.unidades
		FROM
			Categorias
		INNER JOIN (Emisiones
		LEFT JOIN pagos ON
			Emisiones.idregistro = pagos.idregistr) ON
			Categorias.idcategoria = Emisiones.idcategori
		WHERE
			id_cedul = :cedula) AS ooo
	GROUP BY
		idregistro,
		fechaemision,
		id_cedul,
		idcategori,
		claveconcepto,
		concepto,
		monto,
		idproduct,
		unidades
	HAVING
		monto- SUM(pago)<> 0
	UNION 
	SELECT
		id_cedul,
		idcategori,
		claveconcepto,
		fechaemision,
		concepto,
		idregistro,
		0,
		0,
		0
	FROM
		categorias,
		emisiones
	WHERE
		categorias.idcategoria = emisiones.idcategori
		AND idcategori = 19900
		AND	id_cedul = :cedula
		AND idregistro NOT IN (
		SELECT
			DISTINCT idregistr
		FROM
			pagos
		WHERE
			anulado = false)
	 ORDER BY
		id;";
		$data = parent::query($query, $param);
		if (is_array($data)) {
			// we map properties of class to use this info. And send Json object form return.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new \Error("data not found", 404);
		}
		// if all it's okay return the order.
		return $data;
	}
	function getAll($params)
	{
		$pagination = parent::pagination($params);
		// hacemos magiaaaaaaa :V
	}
}