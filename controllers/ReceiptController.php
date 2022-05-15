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
			if (!empty($params['cedula'])) {
				$this->getByStudent($params);
			}
		} else {
			// else $params don't have cedula param
			$this->getAll($params);
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

			if ($data) {
				$this->response->send(["receipts" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * get all receipts.
	 */
	private function getAll($params)
	{
		/**
		 * TO do. :V This is a example response...
		 */
		$this->response->send(["from" => "Desde el endpoint /recibos sin estudiantes"]);
	}
}
