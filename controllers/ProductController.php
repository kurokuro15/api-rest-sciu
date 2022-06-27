<?php

namespace api\controllers;

use api\Models\Order;
use base\controllers\Controller;
use api\Models\Product;
use Error;
use Exception;
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

			$this->response->send(["products" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function get($params)
	{
		$params = array_merge($params, $this->request->get());
		try {
			$data = $this->products->getAll($params);
			if(isset($this->products->pages) && count($this->products->pages) > 0) {
				$this->getMeta(["prev" => $this->products->pages["prev"], "next" => $this->products->pages["next"], "count" => $this->products->pages["count"]]);
			}
			$this->response->send(["products" => $data]);
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
			throw new Error("Error, falta el id del producto", 400);
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
			if (!isset($params['id'])) {
				throw new Exception("Error, falta el identificador del producto", 400);
			}
			$order = new Order;
			$total = $order->getByProduct($params['id']);
			if ($total['total'] > 0)
				throw new Exception("No puede eliminar el producto, tiene ordenes asociadas", 400);
			$data = $this->products->delete($params);

			$this->response->send(["products" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
