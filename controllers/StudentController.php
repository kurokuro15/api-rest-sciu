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

	public function get($params = [])
	{
		// need refactor this section. to Limit and offset and maybe external class middleware
		$params = array_merge($params, $this->request->get());
		try {
			list($data, $meta) = $this->students->getAll($params);

			parent::getMeta($meta);

			if ($data) {
				$this->response->send(["students" => $data]);
			}
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
