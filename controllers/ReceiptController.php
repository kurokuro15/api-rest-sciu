<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Receipt;
use Throwable;

/**
 * Clase Controlador para el Endpoint "Recibos"
 */
class ReceiptController extends Controller
{
	/**
	 * Instance of Receipt Model class
	 */
	protected $receipt;

	function __construct()
	{
		parent::__construct();
		$this->receipt = new Receipt;
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
			// else $params don't have cedula param
			$this->getAll($params);
		}
	}

	/**
	 * Retrieve an order by 'order' number
	 */
	public function retrieve($params)
	{
		// validate that param 'order' exist
		if (empty($params) || empty($params['receipt'])) {
			$this->response->send(["error" => "receipt identity field was not send"], 400);
		}

		try {
			$data = $this->receipt->get($params['receipt']);

			$this->response->send(["receipts" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * retrieve all receipts of a student.
	 */
	private function getByStudent($params)
	{
		// validate that param 'cedula' exist
		if (empty($params) || empty($params['cedula'])) {
			$this->response->send(["error" => "Cedula field was not send"], 400);
		}

		try {
			$data = $this->receipt->getByStudent($params['cedula']);

			$this->response->send(["receipts" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * get all receipts.
	 */
	private function getAll($params)
	{
		$params = array_merge($params, $this->request->get());
		try {
			list($data, $meta) = $this->receipt->getAll($params);

			parent::getMeta($meta);

			$this->response->send(["receipts" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function delete($params)
	{
		// validate that param 'order' exist
		if (empty($params) || empty($params['receipt'])) {
			$this->response->send(["error" => "receipt identity field was not send"], 400);
		}

		try {
			$data = $this->receipt->delete($params['receipt']);
			$data = $this->receipt->get($data['receipt']);
			$this->response->send(["receipts" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
