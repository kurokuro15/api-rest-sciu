<?php

namespace api\Controllers;

use base\controllers\Controller;
use api\Models\Category;
use api\Models\Order;
use Exception;
use Throwable;

class CategoryController extends Controller
{

	protected $categories;

	function __construct()
	{
		parent::__construct();
		$this->categories = new Category;
	}

	public function retrieve($params)
	{
		// validate that param 'category' exist
		if (empty($params) || empty($params['category'])) {
			$this->response->send(["error" => "category field was not send"], 400);
		}
		try {
			$data = $this->categories->get($params['category']);
			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function get($params)
	{
		$params = array_merge($params, $this->request->get());
		try {
			list($data, $meta) = $this->categories->getAll($params);
			if (count($meta) > 0) {
				parent::getMeta($meta);
			}
			
			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function create($params)
	{
		// extraígo la data...
		$input = $this->request->input(); //solo puede ser una categoria a la vez...
		try {
			$index = $this->categories->insert($input);
			$data = $this->categories->get($index);

			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function update($params)
	{
		if (!isset($params['id']))
			throw new Exception("Error, falta el id de la categoría", 400);
		// extraígo la data...
		$input = $this->request->input();
		$input['id'] = $params['id'];
		try {
			$index = $this->categories->update($input);
			$data = $this->categories->get($index);

			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function delete($params)
	{
		try {
			if (!isset($params['id'])) {
				throw new Exception("Error, falta el identificador de la categoría", 400);
			}
			$order = new Order;
			$total = $order->getByCategory($params['id']);
			if ($total['total'] > 0)
				throw new Exception("No puede eliminar la categoría, tiene ordenes asociadas", 400);
			$data = $this->categories->delete($params);

			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
