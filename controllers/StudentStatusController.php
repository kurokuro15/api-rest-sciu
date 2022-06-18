<?php

namespace api\Controllers;

use api\Models\StudentStatus;
use base\controllers\Controller;
use Throwable;

class StudentStatusController extends Controller
{
	protected $studentStatus;
	function __construct()
	{
		parent::__construct();
		$this->studentStatus = new StudentStatus;
	}

	function get($params)
	{
		try {
			$data = $this->studentStatus->getAll($params);
			$this->response->send(["studentStatus" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
