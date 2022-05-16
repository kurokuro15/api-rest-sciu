<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Charge;
use Exception;
use Throwable;
use ValueError;

class ChargeController extends Controller
{
	/**
	 * Instance of Order Model class
	 */
	protected $charge;

	function __construct()
	{
		parent::__construct();
		$this->charge = new Charge;
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
			} else if (!empty($params['receipt'])) {
				$this->getByReceipt($params);
			}
		} else {
			$this->getAll();
			// else if $params don't have cedula param
		}
	}

	/**
	 * Retrieve an order by 'order' number
	 */
	public function retrieve($params)
	{
		// validate that param 'order' exist
		if (empty($params) || empty($params['charge'])) {
			$this->response->send(["error" => "charge field was not send"], 400);
		}

		try {
			$data = $this->charge->get($params['charge']);

			if ($data) {
				$this->response->send(["charges" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	
	/**
	 * retrieve all charges of a student.
	 */
	private function getByStudent($params)
	{
		// validate that param 'cedula' exist
		if (empty($params) || empty($params['cedula'])) {
			$this->response->send(["error" => "Cedula field was not send"], 400);
		}

		try {
			$data = $this->charge->getByStudent($params['cedula']);

			if ($data) {
				$this->response->send(["charges" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	
	/**
	 * retrieve all  charges of a receipt.
	 */
	private function getByReceipt($params)
	{
		// validate that param 'receipt' exist
		if (empty($params) || empty($params['receipt'])) {
			$this->response->send(["error" => "Receipt field was not send"], 400);
		}

		try {
			$data = $this->charge->getByReceipt($params['receipt']);

			if ($data) {
				$this->response->send(["charges" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	
	/**
	 * retrieve all charges.
	 */
	private function getAll()
	{
		/**
		 * TO do. :V This is a example response...
		 */
		$this->response->send(["from" => "Desde el endpoint /cobros sin estudiantes"]);
	}

	/**
	 * create an unpaid order
	 */
	function create($params)
	{
		try {
			$charge = $this->request->input();
			$result = $this->charge->insert($charge);

			$data = $this->charge->get($result);
			$this->response->send(["charges" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
