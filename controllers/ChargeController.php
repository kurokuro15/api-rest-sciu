<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Charge;
use \api\Models\Payment;
use Error;
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
		$this->payment = new Payment;
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
			// extraígo la data...
			$charge = $this->request->input(); // not an array por los momentos

			//validamos que exista y sea un array el att "method" y lo extraemos
			if (empty($charge["method"]) || !is_array($charge["method"])) {
				throw new Exception("No se a conseguido ningún método", 403);
			}

			foreach ($charge["method"] as $key => $method) {
				$charge[$key] = $method;
			}

			// validar si el idtipodepago existe
			if (isset($charge['deposit'])) {
				try {
					// validamos el metodo de pago del pago
					$deposit = $this->payment->get($charge['deposit']);
				} catch (Error $err) {
					if ($err->getMessage() === "data not found")
						// registro metodo de pago del pago
						$inserted = $this->payment->insert($charge);
				}
			}

			// registro el pago. 
			if (isset($deposit["payment"]) || isset($inserted)) {
				$data = $this->charge->insert($charge);
			} else  throw new Error("No se pudo validar el idtipodepago", 500);

			//devuelvo los pagos por el id de recibo
			$this->response->send(["charges" => ["id" => $data]]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
