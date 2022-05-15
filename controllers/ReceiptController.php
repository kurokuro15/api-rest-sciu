<?php
namespace api\controllers;
use \base\controllers\Controller;
use \api\Models\Receipt;
class ReceiptController extends Controller {
	protected $receipt;

	function __construct()
	{
		parent::__construct();
		$this->receipt = new Receipt;	
	}
	/**
	* This function handler the get methods to redirect an correctly method...
  */
	public function get($params) {
		// If $params have cedula param 
		if(!empty($params)) {
			if(!empty($params['cedula'])) {
				$this->getByStudent($params);
			} 
			} else {
				$this->getAll();
		// else if $params don't have cedula param
	}
}

/**
 * retrieve all outstanding order of a student.
 */
private function getByStudent($params) { 
	// validate that param 'cedula' exist
	if(empty($params) || empty($params['cedula'])){
		$this->response->send(["error" => "Cedula field was not send"],400);
	}

	try{
		$data = $this->receipt->getByStudent($params['cedula']);
			
		if($data) {
				$this->response->send(["receipts" => $data]);
			}
	} catch(\Throwable $err){
		$this->response->send(["error" => $err->getMessage()], $err->getCode());
	}
}

private function getAll(){
	$this->response->send(["from"=>"Desde el endpoint /recibos sin estudiantes"]);
}
}