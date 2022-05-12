<?php
namespace api\controllers;
use \base\controllers\Controller;
use \api\Models\Order;

class OrderController extends Controller { 
	protected $order;

	function __construct()
	{
		parent::__construct();
		$this->order = new Order;	
	}

	public function retrieve($params){
			// validate that param 'order' exist
			if(empty($params) || empty($params['order'])){
				$this->response->send(["error" => "order field was not send"],400);
			}
	
			try{
				$data = $this->order->get($params['order']);
					
				if($data) {
						$this->response->send($data);
					}
					
			} catch(\Throwable $err){
				$this->response->send(["error" => $err->getMessage()], $err->getCode());
			}
	
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
			$data = $this->order->getByStudent($params['cedula']);
				
			if($data) {
					$this->response->send($data);
				}
		} catch(\Throwable $err){
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	private function getAll(){
		$this->response->send(["from"=>"Desde el endpoint /ordenes sin estudiantes"]);
	}
}