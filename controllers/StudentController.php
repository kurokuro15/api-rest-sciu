<?php
namespace api\Controllers;
use base\controllers\Controller;
use api\Models\Student;

class StudentController extends Controller {
	private $students; 
	function __construct() { 
		parent::__construct();
		$this->students = new Student;
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
}
