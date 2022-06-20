<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Order;
use Exception;
use Throwable;

/**
 * Clase Controlador para el Endpoint "Ordenes"
 */
class OrderController extends Controller
{
	/**
	 * Instance of Order Model class
	 */
	protected $order;

	function __construct()
	{
		parent::__construct();
		$this->order = new Order;
	}

	/**
	 * This function handler the get methods to redirect an correctly method...
	 */
	public function get($params)
	{
		// If $params have cedula param 
		if (!empty($params)) {
			if (isset($params['cedula'])) {
				$this->getByStudent($params);
			}
		} else {
			$this->getAll($params);
			// else if $params don't have cedula param
		}
	}

	/**
	 * Retrieve an order by 'order' number
	 */
	public function retrieve($params)
	{
		// validate that param 'order' exist
		if (empty($params) || empty($params['order'])) {
			$this->response->send(["error" => "order field was not send"], 400);
		}

		try {
			$data = $this->order->get($params['order']);

			$this->response->send(["orders" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * retrieve all outstanding order of a student.
	 */
	private function getByStudent($params)
	{
		// validate that param 'cedula' exist
		if (empty($params) || empty($params['cedula'])) {
			$this->response->send(["error" => "Cedula field was not send"], 400);
		}

		try {
			$data = $this->order->getByStudent($params['cedula']);

			$this->response->send(["orders" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * retrieve all outstanding orders.
	 */
	private function getAll($params)
	{
		try{
			$params = array_merge($params, $this->request->get());
			$data = $this->order->getAll($params);
			if(isset($this->order->pages) && count($this->order->pages) > 0) {
				$this->getMeta(["prev" => $this->order->pages["prev"], "next" => $this->order->pages["next"], "count" => $this->order->pages["count"]]);
			}
			$this->response->send(["orders" => $data]);

		}catch(Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * create an unpaid order
	 */
	function create($params)
	{
		try {
			$order = $this->request->input();
			$result = $this->order->insert($order);
			$data = $this->order->get($result);

			$this->response->send(["orders" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
