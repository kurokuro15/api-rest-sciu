<?php

namespace api\Models;

use \base\Models\Model;
use Error;
use ValueError;

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
	public function get($registro)
	{
		//Validate param
		if (!isset($registro) || (int) $registro === 0) {
			throw new ValueError("Collection order number is not a valid number", 401);
		}
		// map param in a array
		$param = [":registro" => $registro];

		// prepare query
		// Query retrieve the order detail by this id.
		$query = "SELECT
			idregistro AS id,
			fechaemision AS reg_date,
			concepto AS concept,
			monto AS amount,
			id_cedul AS cedula,
			unidades as units,
			nombrecategoria AS category
		FROM
			emisiones
		LEFT JOIN categorias ON
			idcategori = idcategoria
		WHERE
			idregistro = :registro;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (is_array($data)) {
			// we map properties of class to use this info. And send return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		} else {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the order.
		return $data[0];
	}

	/**
	 * Get all pending collection orders by Student
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (int) $cedula === 0) {
			throw new \ValueError("Cedula number is not a valid number", 401);
		}
		// map param in a array
		$param = [":cedula" => $cedula];
		// prepare query

		// query to get all pending collection orders. 
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

		// Validate data
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the order.
		return $data;
	}

	/**
	 * Get all Orders
	 */
	public function getAll($params)
	{
		$pagination = parent::pagination($params);
		// hacemos magiaaaaaaa :V
	}
}
