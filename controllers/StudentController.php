<?php

namespace api\Controllers;

use base\controllers\Controller;
use api\Models\Student;
use Throwable;

/**
 * Clase Controlador para el Endpoint "Estudiantes"
 */
class StudentController extends Controller
{
	/**
	 * Instance of Student Model class
	 */
	private $students;

	function __construct()
	{
		parent::__construct();
		$this->students = new Student;
		$this->setQueryParams();
	}

	/* Handler to get a student from Db */
	public function retrieve($params)
	{
		// validate that param 'cedula' exist
		if (empty($params) || empty($params['cedula'])) {
			$this->response->send(["error" => "Cedula field was not send"], 400);
		}

		try {
			$data = $this->students->get($params['cedula']);

			if ($data) {
				$this->response->send(["students" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function get($params)
	{
		// need refactor this section. to Limit and offset and maybe external class middleware
		if (isset($this->queryParams['page'])) $params['page'] =  $this->queryParams['page'];
		if (isset($this->queryParams['records'])) $params['records'] =  $this->queryParams['records'];

		try {
			$data = $this->students->getAll($params);

			if ($data) {
				$this->response->send(["students" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
