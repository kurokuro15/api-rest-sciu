<?php

namespace api\Models;

use base\models\Model;
use Error;
use Exception;

class Category extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Retrieve a Student by 'cedula'
	 */
	public function get($id)
	{
		//Validate param
		if (!isset($id) || (int) $id === 0) {
			throw new Error("Identity of category is not a valid number", 400);
		}
		// map param in a array
		$param = [":id" => $id];

		// query to get all detail of a Student
		$query = "SELECT
				idcategoria AS id,
				nombrecategoria AS category,
				colorcategoria AS color
			From
				categorias
			WHERE 
				idcategoria = :id";

		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		//validate data
		if (is_array($data)) {
			// Map properties of class to use this info. And return object.
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
			// if all it's okay return the student.
			return $data[0];
		} else {
			throw new Error("Not Found", 404);
		}
	}

	public function getAll($params)
	{
		//dentro de param vendría la página
		$query = "SELECT
		idcategoria AS id,
		nombrecategoria AS category,
		colorcategoria AS color
	From
		categorias
	ORDER BY
		nombrecategoria ASC,
		idcategoria ASC";

		/**
		 * Si es necesario, se añade la paginación aquí
		 */

		$data = parent::query($query);
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return $data;
	}

	public function insert($category)
	{
		$params = [];
		$query = "INSERT INTO categorias(idcategoria, nombrecategoria, colorcategoria) VALUES (:id, :category, :color)RETURNING idcategoria as id";

		if (empty($category['category'])) {
			throw new Exception("Error, falta el nombre de la categoría", 400);
		} else {
			$params['category'] = $category['category'];
		}

		//set default color or request color
		if (empty($category['color'])) {
			$whiteColor = 'ffffff';
			$params['color'] = $whiteColor;
		} else {
			$params['color'] = $category['color'];
		}
		//validate id or create one
		if (empty($category['id'])) {
			$params['id'] = $this->getLastCategoryId()['id'] + 1;
		} else {
			$params['id'] = $category['id'];
		}

		$result = parent::query($query, $params);
		return $result[0];
	}

	private function getLastCategoryId()
	{
		$query = "select
		case
			when max(idcategoria) is null then 1
			else max(idcategoria)
		end as id
	from
		categorias";

		$data = parent::query($query);
		if ($data) {
			return $data[0];
		}
	}
}
