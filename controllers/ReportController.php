<?php

namespace api\Controllers;

use api\Models\Report;
use base\controllers\Controller;
use Throwable;

class ReportController extends Controller
{
	protected $report;

	function __construct()
	{
		parent::__construct();
		$this->report = new Report;
	}

	/**
	 * Behaivor for get reports from a GET request
	 */
	public function get($params)
	{
		try {
			$queryParams = $this->request->get();
			$reportType = $queryParams["reportType"];

			switch ($reportType) {
				case "1":
				case "2":
					$this->cashRegister($queryParams);
					break;
				case "3":
					$this->detailed($queryParams);
					break;
				default:
					$this->response->send(["error" => "Invalid report type"], 400);
					break;
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * Handler for 'Arqueo de Caja' report.
	 */
	public function cashRegister($params)
	{
		$queryParams = $this->request->get();
		try {
			$data = $this->report->getCashRegister($queryParams);

			$header = $this->constructReportHeader($queryParams, $params);

			$this->response->send(["reports" => $data, "header" => $header]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * Handler for 'Informe detallado' report.
	 */
	public function detailed($params)
	{
		$queryParams = $this->request->get();
		try {
			$data = $this->report->getDetailed($queryParams);

			$header = $this->constructReportHeader($queryParams, $params);

			$this->response->send(["reports" => $data, "header" => $header]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	/**
	 * Constructor a header report with receipt and orders counts and other info
	 * @param  array $queryParams
	 * @param  array $params
	 * @return array
	 */
	function constructReportHeader($queryParams = [], $params = [])
	{
		// prepare result array 
		$data = [];
		// search all receipt and charges number of given interval
		// an array like: [[receipt] => receipt_number] and [[charge] => charge_number]

		// enter de first and last receipt number and total count.
		$_receipts = $this->report->getReceiptInterval($queryParams);
		if (!is_array($_receipts))
			return $data;
		// validate that  $_receipts have more than one element
		if (count($_receipts) > 0) {
			$data['receipts'] = [
				'first' => $_receipts[0]['receipt'],
				'last' => $_receipts[count($_receipts) - 1]['receipt'],
				'count' => count($_receipts)
			];
		} else {
			$data['receipts'] = [
				'first' => 0,
				'last' => 0,
				'count' => 0
			];
		}

		// enter de first and last charge number and total count.
		$_charges = $this->report->getChargeInterval($queryParams);
		if (!is_array($_charges))
			return $data;
		// validate that $_charges have more than one element
		if (count($_charges) > 0) {
			$data['charges'] = [
				'first' => $_charges[0]['charge'],
				'last' => $_charges[count($_charges) - 1]['charge'],
				'count' => count($_charges)
			];
		} else {
			$data['charges'] = [
				'first' => 0,
				'last' => 0,
				'count' => 0
			];
		}

		return $data;
	}
}
