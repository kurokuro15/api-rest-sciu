<?php

namespace api\Controllers;

use api\Models\Report;
use base\controllers\Controller;
use Exception;
use Throwable;

class ReportController extends Controller
{
	protected $report;
	function __construct()
	{
		parent::__construct();
		$this->report = new Report;
	}

	public function get($params)
	{
		$queryParams = $this->request->get();
		try {
			$data = $this->report->get($queryParams);
			
			$header = $this->constructReportHeader($queryParams);
			
			$this->response->send(["reports" => $data, "header" => $header]);

		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}


	/**
	 * Constructor a header report with receipt and orders counts and other info
	 * @param  array $queryParams
	 * @param  string $query
	 * @return array
	 */
	function constructReportHeader($queryParams =[],$params =[]) {
		// prepare result array 
		$data = [];
		// search all receipt and charges number of given interval
		// an array like: [[receipt] => receipt_number] and [[charge] => charge_number]
		
		// enter de first and last receipt number and total count.
		$_receipts = $this->report->getReceiptInterval($queryParams);

		// validate that  $_receipts have more than one element
		if (count($_receipts) > 1) {
			$data['receipts'] = [
				'first' => $_receipts[0]['receipt'],
				'last' => $_receipts[count($_receipts) - 1]['receipt'],
				'count' => count($_receipts)
			];
		} else if(count($_receipts) == 1){
			$data['receipts'] = [
				'first' => $_receipts[0]['receipt'],
				'last' => $_receipts[0]['receipt'],
				'count' => 1
			];
		} else  {
			$data['charges'] = [
				'first' => 0,
				'last' => 0,
				'count' => 0
			];
		}

		// enter de first and last charge number and total count.
		$_charges = $this->report->getChargeInterval($queryParams);
		// validate that $_charges have more than one element
		if (count($_charges) > 1) {
			$data['charges'] = [
				'first' => $_charges[0]['charge'],
				'last' => $_charges[count($_charges) - 1]['charge'],
				'count' => count($_charges)
			];
		} else if(count($_charges) == 1) {
			$data['charges'] = [
				'first' => $_charges[0]['charge'],
				'last' => $_charges[0]['charge'],
				'count' => 1
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
