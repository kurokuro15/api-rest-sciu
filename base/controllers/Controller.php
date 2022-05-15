<?php
namespace base\controllers;
use base\https\Response;
use base\https\Request;

/**
 * Archivo de Controlador base.
 */
class Controller
{
	/**
	 * Request Class.
	 */
	protected $request;

	/**
	 * Response Class.
	 */
	protected $response;
	/**
	 * Query Params map array
	 */
	protected $queryParams = [];
	/**
	 *  Construct
	 */
	public function __construct()
	{
		$this->request = $GLOBALS['request'];
		$this->response = $GLOBALS['response'];
	}

	protected function setQueryParams() {
		if(!empty($_GET)) { 
			foreach($_GET as $k => $v) {
				$this->queryParams[$k] = $v;
			}
		}
		return count($this->queryParams);
	}
}
