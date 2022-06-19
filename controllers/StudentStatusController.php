<?php

namespace api\Controllers;

use api\Models\Student;
use api\Models\StudentStatus;
use base\controllers\Controller;
use Error;
use Throwable;

class StudentStatusController extends Controller
{
	protected $studentStatus;
	function __construct()
	{
		parent::__construct();
		$this->studentStatus = new StudentStatus;
	}

	function retrieve($params)
	{
		try {
			if (!isset($params['id']))
				throw new Error("id is not defined", 400);

			$data = $this->studentStatus->get($params['id']);
			$this->response->send(["status" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error", $err->getMessage()], $err->getCode());
		}
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

	function put($params)
	{
		try {
			$params = array_merge($params, $this->request->input());
			$id = $this->studentStatus->update($params);
			$this->retrieve($id);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	function post($params)
	{
		try {
			$params = array_merge($params, $this->request->input());
			$id = $this->studentStatus->create($params);
			$this->retrieve($id);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	public function delete($params)
	{
		try {
			if (!isset($params['id'])) {
				throw new  Error("Error, falta el identificador de la categoría", 400);
			}
			$student = new Student;
			$total = $student->getByStatus($params['id']);
			if ($total > 0)
				throw new Error("No puede eliminar la categoría, tiene ordenes asociadas", 400);

			$data = $this->studentStatus->delete($params);
			$this->response->send(["studentStatus" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
