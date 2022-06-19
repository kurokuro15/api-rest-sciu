<?php

namespace api\Controllers;

use base\controllers\Controller;
use api\Models\Career;
use api\Models\Student;
use Exception;
use Throwable;

class CareerController extends Controller
{
	protected $career;
	function __construct()
	{
		parent::__construct();
		$this->career = new Career;
	}
	/**
	 * Retrieve a Career by id
	 */
	public function retrieve($params)
	{
		// validate that param 'category' exist
		if (empty($params) || empty($params['career'])) {
			$this->response->send(["error" => "career field was not send"], 400);
		}
		try {
			$data = $this->career->get($params['career']);
			$this->response->send(["careers" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	/**
	 * Get all Careers
	 */
	public function get($params)
	{
		try {
			$data =  $this->career->getAll($params);

			$this->response->send(["careers" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	/**
	 * Create a new Career
	 */
	public function create($params)
	{
		// extraígo la data...
		$input = $this->request->input(); //solo puede ser una categoria a la vez...
		try {
			$index = $this->career->create($input);
			$data = $this->career->get($index);

			$this->response->send(["careers" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	/**
	 * Update a Career
	 */
	public function update($params)
	{
		if (!isset($params['id'])) {
			$this->response->send(["error" => "id field was not send"], 400);
		}
		// extraígo la data...
		$input = $this->request->input();
		$input['id'] = $params['id'];
		try {
			$index = $this->career->update($input);
			$data = $this->career->get($index);

			$this->response->send(["careers" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
	/**
	 * Delete a Career
	 */
	public function delete($params)
	{
		if (!isset($params['id'])) {
			$this->response->send(["error" => "id field was not send"], 400);
		}
		try {
			$student = new Student;
			$total = $student->getByCareer($params['id']);

			if ($total > 0)
				throw new Exception("No puede eliminar la carera, tiene estudiantes asociados", 400);

			$data = $this->career->delete($params);
			$this->response->send(["careers" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
