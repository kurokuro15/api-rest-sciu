<?php
namespace api\Controllers;

use api\Models\Career;
use base\controllers\Controller;
use api\Models\Coordination;
use api\Models\Student;
use Error;
use Exception;
use Throwable;

class CoordinationController extends Controller
{
	protected $coordination;
	function __construct()
	{
		parent::__construct();
		$this->coordination = new Coordination;
	}
	/**
	 * Retrieve a Coordination by id
	 */
	public function retrieve($params) {
			// validate that param 'category' exist
			if (empty($params) || empty($params['coordination'])) {
				$this->response->send(["error" => "career field was not send"], 400);
			}
			try {
				$data = $this->coordination->get($params['coordination']);
				$this->response->send(["coordinations" => $data]);
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
			$data =  $this->coordination->getAll($params);

			$this->response->send(["coordinations" => $data]);
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
			$index = $this->coordination->create($input);
			$data = $this->coordination->get($index);

			$this->response->send(["coordinations" => $data]);
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
			$index = $this->coordination->update($input);
			$data = $this->coordination->get($index);

			$this->response->send(["coordinations" => $data]);
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
			$career = new Career;
			$total = $career->getByCoordination($params['id']);

			if ($total > 0)
				throw new Exception("No puede eliminar la coordinación, tiene carreras asociadas", 400);

			$data = $this->coordination->delete($params['id']);
			$this->response->send(["coordinations" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}

