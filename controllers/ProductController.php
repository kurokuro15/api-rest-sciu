<?php

namespace api\controllers;

use base\controllers\Controller;
use api\Models\Product;
use Error;
use Throwable;

class ProductController extends Controller
{
	protected $products;
	function __construct()
	{
		parent::__construct();
		$this->products = new Product;
	}
	public function retrieve($params)
	{
		// validate that param "product" exist
		if (empty($params) || empty($params['product'])) {
			$this->response->send(["error" => "product field was not send"], 400);
		}
		try {
			$data = $this->products->get($params['product']);
			if ($data) {
				$this->response->send(["products" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function get($params)
	{
		$params = array_merge($params, $this->request->get());
		try {
			list($data, $meta) = $this->products->getAll($params);
			parent::getMeta($meta);
			if ($data) {
				$this->response->send(["products" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function create($params)
	{
		// extraígo la data...
		$input = $this->request->input(); //solo puede ser un producto a la vez...
		try {
			$index = $this->products->insert($input);
			$data = $this->products->get($index);
			$this->response->send(["products" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function update($params)
	{
		if (!isset($params['id']))
			throw new Error("Error, falta el id de la categoría", 400);
		// extraígo la data...
		$input = $this->request->input();
		$input['id'] = $params['id'];
		try {
			$index = $this->products->update($input);
			$data = $this->products->get($index);
			$this->response->send(["products" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function delete($params)
	{
		try {
			$data = $this->products->delete($params);
			$this->response->send(["products" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
