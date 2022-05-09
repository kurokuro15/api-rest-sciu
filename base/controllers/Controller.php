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
	public $request;

	/**
	 * Response Class.
	 */
	public $response;

	/**
	 *  Construct
	 */
	public function __construct()
	{
		$this->request = new Request;
		$this->response = new Response;
	}
}
