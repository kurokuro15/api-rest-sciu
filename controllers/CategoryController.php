<?php

namespace api\Controllers;

use base\controllers\Controller;
use api\Models\Category;
use Throwable;

class CategoryController extends Controller
{

	protected $categories;

	function __construct()
	{
		parent::__construct();
		$this->categories = new Category;
		$this->setQueryParams();
	}

	public function retrieve($params)
	{
		// validate that param 'order' exist
		if (empty($params) || empty($params['category'])) {
			$this->response->send(["error" => "category field was not send"], 400);
		}

		try {
			$data = $this->categories->get($params['category']);

			if ($data) {
				$this->response->send(["categories" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function get($params)
	{
		/**
		 * Si necesitamos paginar las categorías acá debemos pasarle a $params offset y limit...
		 */
		try {
			$data = $this->categories->getAll($params);

			if ($data) {
				$this->response->send(["categories" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function create($params)
	{
		// extraígo la data...
		$input = $this->request->input(); //solo puede ser una categoria a la vez...

		try {
			$index = $this->categories->insert($input)['id'];
			$data = $this->categories->get($index);
			$this->response->send(["categories" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
