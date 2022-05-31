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
			$this->response->send(["reports" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
