<?php

namespace api\Controllers;

use api\Models\Parameter;
use base\controllers\Controller;
use Throwable;

class ParameterController extends Controller
{
	protected $parameter;
	function __construct()
	{
		parent::__construct();
		$this->parameter = new Parameter;
	}

	public function get($params)
	{
		try {
			$data = $this->parameter->get();
			$this->response->send(["parameters" => $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function post($params)
	{
		try {
			$params = array_merge($params, $this->request->input());
			$this->parameter->create($params);
			$data = $this->parameter->get();
			$this->response->send(["parameters" =>  $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function put($params)
	{
		try {
			$params = array_merge($params, $this->request->input());
			$this->parameter->update($params);
			$data = $this->parameter->get();
			$this->response->send(["parameters" =>  $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function delete($params)
	{
		try {
			$this->parameter->delete($params);
			$data = $this->parameter->get();
			$this->response->send(["parameters" =>  $data]);
		} catch (Throwable $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
