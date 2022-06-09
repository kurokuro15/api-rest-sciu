<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Charge;
use \api\Models\Payment;
use Error;
use Exception;
use Throwable;

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

			$this->response->send(["charges" => $data]);
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

			$this->response->send(["charges" => $data]);
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

			$this->response->send(["charges" => $data]);
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
		$this->response->send(["desde" => "Desde el endpoint /cobros sin estudiantes"]);
	}


	/**
	 * create an unpaid order
	 */
	function create($params)
	{
		// extraígo la data...
		$input = $this->request->input(); // Array de Ordenes (emisiones)

		// preparamos el número de factura.
		try {
			$receipt = $this->charge->getLastReceipt();
			$receipt["receipt_number"] = $receipt["receipt_number"] + 1;
			if (is_array($input)) {
				foreach ($input as $charge) {
					$charge["receipt_number"] = $receipt["receipt_number"];
					//validamos que exista y sea un array el att "method" y lo extraemos
					if (empty($charge["method"]) || !is_array($charge["method"])) {
						throw new Exception("No se a conseguido ningún método", 400);
					}

					//Aplanamos el objeto/propiedad "method" dentro de $charge
					foreach ($charge["method"] as $key => $method) {
						$charge[$key] = $method;
					}

					// validar si el "deposit" (idtipodepago) existe
					if (isset($charge['deposit'])) {
						try {
							// validamos el metodo de pago del pago
							$deposit= $this->payment->get($charge['deposit']);
							$charge['deposit'] = $deposit['deposit'];

						} catch (Error $err) {
							if ($err->getCode() === 404)
								// registro metodo de pago del pago
								$inserted = $this->payment->insert($charge);
								$charge['deposit'] = $inserted['deposit'];
						}
					}

					// registro el pago. 
					if (empty($charge['deposit']))
							throw new Error("No se pudo validar el campo 'deposit' (idtipodepago)", 500);
					
					$data = $this->charge->insert($charge);
					//devuelvo los pagos por el id de recibo
					$res["charges"][] = $data;
				}
			}
			$res["receipt_number"] = $receipt["receipt_number"];

			$this->response->send($res);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
