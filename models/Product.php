<?php

namespace api\Models;

use base\models\Model;
use Error;

class Product extends Model
{
	function __construct()
	{
		parent::__construct();
	}
	public function get($id)
	{
		// Validate param
		if (!isset($id) || (int) $id === 0) {
			throw new Error("Identity of product is not a valid number", 400);
		}
		// map param in a array
		$param = [
			":id" => $id
		];
		// query to get all detail of a Product
		$query = "SELECT
				idproducto AS id,
				nombreproducto AS name,
				cantidad AS stock,
				contable AS contable,
				preciounitario AS price,
				idcategor AS category,
				vecesusado as used
			From
				productos
			WHERE 
				idproducto = :id";
		// retrieve data and save in an variable
		$data = parent::query($query, $param);
		// validate data
		if (count($data) > 0) {
			// if all it's okay return the product.
			return $data[0];
		} else {
			throw new Error("Not Found", 404);
		}
	}
	public function getAll($params)
	{
		// dentro de param vendría la página
		$query = "SELECT
		idproducto AS id,
		nombreproducto AS name,
		cantidad AS stock,
		preciounitario AS price,
		idcategor AS category
		From
		productos";
		// retrieve data and save in an variable
		//Add pagination to query
		list($interval, $placeholder, $meta) = parent::pagination($params, false);
		$params = array_merge($params, $interval);

		// Obtenemos el total de elementos de la query y lo guardamos en meta
		$meta["count"] = $this->count($query);

		// añadimos el placeholder de paginación		
		$query .= $placeholder;
		$data = parent::query($query, $params);
		// validate data
		if (count($data) > 0) {
			// if all it's okay return the student.
			return [$data, $meta];
		} else {
			throw new Error("Not Found", 404);
		}
	}
	public function insert($product)
	{
		$params = [];
		$query = "INSERT INTO productos(nombreproducto, cantidad, contable, preciounitario, idcategor, vecesusado) 
		VALUES(:name, :stock, :contable, :price, :category, :used)";

		//set required params
		if (empty($product["name"]))
			throw new Error("Name is required", 400);
		if (empty($product["category"]))
			throw new Error("Category is required", 400);
		if (empty($product["price"]))
			throw new Error("Price is required", 400);

		$params["name"] = $product["name"];
		$params["price"] = $product["price"];
		$params["category"] = $product["category"];

		//setting default/optionals props
		$params["stock"] = isset($product["stock"]) ? $product["stock"] : 0;
		$params["contable"] = isset($product["contable"]) ? $product["contable"] : false;
		$params["used"] = isset($product["used"]) ? $product["used"] : 0;

		$result = parent::nonQuery($query, $params);
		return $result;
	}
	public function update($product)
	{
		$params = [];
		$query = "UPDATE productos SET nombreproducto = :name, cantidad = :stock, contable = :contable, preciounitario = :price, idcategor = :category, vecesusado = :used WHERE idproducto = :id RETURNING idproducto as id";

		//set required params
		if (empty($product["id"]))
			throw new Error("Id is required", 400);
		if (empty($product["name"]))
			throw new Error("Name is required", 400);
		if (empty($product["category"]))
			throw new Error("Category is required", 400);
		if (empty($product["price"]))
			throw new Error("Price is required", 400);
		if (empty($product["stock"]))
			throw new Error("stock is required", 400);
		if (empty($product["used"]))
			throw new Error("used is required", 400);
		if (empty($product["contable"]) && $product["contable"] != false)
			throw new Error("contable is required", 400);

		$params["category"] = $product["category"];
		$params["contable"] = $product["contable"];
		$params["id"] = $product["id"];
		$params["name"] = $product["name"];
		$params["price"] = $product["price"];
		$params["stock"] = $product["stock"];
		$params["used"] = $product["used"];

		$result = parent::query($query, $params);
		if(isset($result))
			return $result[0]["id"];
	}
	public function delete($product)
	{
		if (empty($product['id']))
			throw new Error("Error, falta el identificador del producto", 400);

		$params = [];
		$query = "DELETE FROM productos WHERE idproducto = :id";
		$params["id"] = $product["id"];
		$result = parent::query($query, $params);
		return $result;
	}
}
