<?php
namespace api\Controllers;
use base\controllers\Controller;
use api\Models\Student;

class StudentController extends Controller {
	private $students; 
	function __construct() { 
		parent::__construct();
		$this->students = new Student;
		$this->getQueryParams();
	}
	/* Handler to get a student of Db */
	public function retrieve($params) {
		// validate that param 'cedula' exist
		if(empty($params) || empty($params['cedula'])){
			$this->response->send(["error" => "Cedula field was not send"],400);
		}

		try{
			$data = $this->students->get($params['cedula']);
				
			if($data) {
					$this->response->send($data);
				}
				
		} catch(\Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}

	}

	public function get($params) {
			$page = isset($this->queryParams['page']) ? $this->queryParams['page'] : 1;
			$records = isset($this->queryParams['records']) ? $this->queryParams['records'] : 5;

		try{
			$data = $this->students->getAll($page, $records);
				
			if($data) {
					$this->response->send($data);
				}
				
		} catch(\Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
