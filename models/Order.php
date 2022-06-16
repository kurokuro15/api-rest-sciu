<?php

namespace api\Models;

use \base\Models\Model;
use Error;
use Exception;

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
	 * Retrieve from Order (emisiones)
	 */
	public function get($registro)
	{
		//Validate param
		if (!isset($registro) || (empty($registro) && !is_numeric($registro))) {
			throw new Error("Collection order number is not a valid number", 400);
		}
		// map param in a array
		$param = [":registro" => $registro];

		// prepare query
		// Query retrieve the order detail by this id.
		$query = "SELECT
			idregistro AS id,
			fechaemision AS reg_date,
			concepto AS concept,
			claveconcepto AS username,
			monto AS outstanding,
			id_cedul AS cedula,
			unidades as units,
			idcategoria AS id_category,
			idproduct as product_id
		FROM
			emisiones
		LEFT JOIN categorias ON
			idcategori = idcategoria
		WHERE
			idregistro = :registro;";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		// we map properties of class to use this info. And send return object.
		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
			// if all it's okay return the order.
		}

		return $data[0];
	}

	/**
	 * Get all pending collection orders by Student
	 */
	public function getByStudent($cedula)
	{
		//Validate param
		if (!isset($cedula) || (empty($cedula) && !is_numeric($cedula))) {
			throw new Error("Cedula number is not a valid number", 400);
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
			idproduct AS product_id,
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
		//validate


		return $data;
	}
	/**
	 * Get total number of orders by an Category
	 */
	public function getByCategory($category)
	{
		if (!isset($category) || (empty($category) && !is_numeric($category))) {
			throw new \Error("Category number is not a valid number", 400);
		}
		$param = [":category" => $category];
		$query = "SELECT
			COUNT(*) as total
		FROM
			emisiones e
		WHERE
			e.idcategori  = :category";
		$data = parent::query($query, $param);
		// validate
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data[0];
	}
	/**
	 * Get total number of orders by an Product id
	 */
	public function getByProduct($product)
	{
		if (!isset($product) || (empty($product) && !is_numeric($product))) {
			throw new \Error("Product number is not a valid number", 400);
		}
		$param = [":product" => $product];
		$query = "SELECT
			COUNT(*) as total
		FROM
			emisiones e
		WHERE
			e.idproduct  = :product";
		$data = parent::query($query, $param);

		// validate
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		return $data[0];
	}
	/**
	 * Get all Orders
	 */
	public function getAll($params)
	{
		// hacemos magiaaaaaaa :V
	}
	/**
	 * Insert an new Order
	 */
	public function insert($order)
	{
		$params = [];
		// validate data
		$required = [
			'category_id',
			'cedula',
			'username',
			'concept',
			'outstanding',
			'product_id',
			'units'
		];
		//Validate & map params
		//validate username, concept, outstanding , units
		//validate product_id
		foreach ($required as $value) {
			if (empty($order[$value]) || (empty($order[$value]) && !is_numeric($order[$value]))) {
				throw new Exception("no se a conseguido el campo $value", 400);
			};
			$params[$value] = $order[$value];
		}

		//validade cedula
		if (!isset($params['cedula']) || (empty($params['cedula']) && !is_numeric($params['cedula']))) {
			throw new Error("the cedula number is not a valid number", 400);
		}

		//set reg_date -4 GMT like 2022-05-15 17:05 Y-M-D H:mm
		$params['reg_date'] = date('Y-m-d H:i');
		// Prepare query
		$query = "INSERT
			INTO
				emisiones (idcategori,
				fechaemision,
				concepto,
				claveconcepto,
				monto,
				id_cedul,
				idproduct,
				unidades)
			VALUES (:category_id,
				:reg_date,
				:concept,
				:username,
				:outstanding,
				:cedula,
				:product_id,
				:units);";

		//insert data 
		$result = parent::nonQuery($query, $params);

		return $result;
		//return success messange or error msg
	}

	//Update por implementar
	//Delete (¿anular?) por implementar
}
