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
	 * Retrieve a Category by id
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
		if (count($data) > 0) {
			// if all it's okay return the student.
			return $data[0];
		} else {
			throw new Error("Not Found", 404);
		}
	}

	/**
	 * Get all Categories
	 */
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

		//Add pagination to query
		list($interval, $placeholder, $meta) = parent::pagination($params, false);
		$params = array_merge($params, $interval);

		// Obtenemos el total de elementos de la query y lo guardamos en meta
		$meta["count"] = $this->count($query);

		// añadimos el placeholder de paginación		
		$query .= $placeholder;
		$data = parent::query($query, $params);
		// validate that have some more zero records
		if (count($data) < 1) {
			throw new Error("data not found", 404);
		}
		// if all it's okay return the student.
		return [$data, $meta];
	}

	/**
	 * Insert a new Category
	 */
	public function insert($category)
	{
		$params = [];
		$query = "INSERT INTO categorias(idcategoria, nombrecategoria, colorcategoria) VALUES (:id, :category, :color) RETURNING idcategoria as id";

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
		return $result[0]["id"];
	}

	/**
	 * Get last 'idcategoria' to insert a new category
	 */
	private function getLastCategoryId()
	{
		$query = "SELECT
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
	/**
	 * Update an category
	 */
	public function update($category)
	{
		if (empty($category['category'])) {
			throw new Exception("Error, falta el nombre de la categoría", 400);
		}
		if (empty($category['color'])) {
			throw new Exception("Error, falta el color de la categoría", 400);
		}
		if (empty($category['id'])) {
			throw new Exception("Error, falta el identificador de la categoría", 400);
		}
		$params['category'] = $category['category'];
		$params['color'] = $category['color'];
		$params['id'] = $category['id'];
		$query = "UPDATE categorias SET nombrecategoria = :category, colorcategoria = :color WHERE idcategoria = :id RETURNING idcategoria as id";
		$result = parent::query($query, $params);
		if (isset($result))
			return $result[0]["id"];
	}

	/**
	 * Delete a category
	 */
	public function delete($category)
	{
		if (empty($category['id'])) {
			throw new Exception("Error, falta el identificador de la categoría", 400);
		}
		$query = "DELETE FROM categorias WHERE idcategoria = :id";
		$params['id'] = $category['id'];
		$result = parent::query($query, $params);
		return $result;
	}
}
