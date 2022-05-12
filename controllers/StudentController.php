<?php
namespace api\Controllers;
use base\controllers\Controller;
use api\Models\Student;

class StudentController extends Controller {
	private $students; 
	function __construct() { 
		parent::__construct();
		$this->students = new Student;
		$this->setQueryParams();
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
					$this->response->send(["students" => $data ]);
				}
				
		} catch(\Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}

	}

	public function get($params) {
			if(isset($this->queryParams['page'])) $params['page'] =  $this->queryParams['page'];
			if(isset($this->queryParams['records'])) $params['records'] =  $this->queryParams['records'];

		try{
			$data = $this->students->getAll($params);
				
			if($data) {
					$this->response->send(["students" => $data]);
				}
				
		} catch(\Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
