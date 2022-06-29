<?php

namespace api\controllers;

use \base\controllers\Controller;
use \api\Models\Exchange;
use Error;

class ExchangeController extends Controller
{
	private $exchange;
	function __construct()
	{
		parent::__construct();
		$this->exchange = new Exchange;
	}

	public function retrieve($params)
	{
		if (!isset($params['id']) || !is_numeric($params['id'])) {
			throw new Error("Identity of exchange is not a valid number", 400);
		}
		try {
			$id = $params['id'];
			$data = $this->exchange->get($id);
			$this->response->send(["exchanges" => $data]);
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function get($params)
	{
		try {
			$params = array_merge($params, $this->request->get());

			if (array_key_exists('offset', $params) && array_key_exists('limit', $params))
				$data = $this->exchange->getAll($params);
			else
				$data = $this->exchange->getLast();
			if (isset($this->exchange->pages) && count($this->exchange->pages) > 0)
				$this->getMeta([
					"prev" => $this->exchange->pages["prev"],
					"next" => $this->exchange->pages["next"],
					"count" => $this->exchange->pages["count"]
				]);

			$this->response->send(["exchanges" => $data]);
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function put($params)
	{
		try {
			if (!isset($params['id']) || !is_numeric($params['id'])) {
				throw new Error("Identity of exchange is not a valid number", 400);
			}
			$input = $this->request->input();
			$input['id'] = $params['id'];
			$id = $this->exchange->update($input);
			$data = $this->exchange->get($id);
			$this->response->send(["exchanges" => $data]);
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function post($params)
	{
		try {
			$input = $this->request->input();
			$id = $this->exchange->insert($input);
			$data = $this->exchange->get($id);
			$this->response->send(["exchanges" => $data]);
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}

	public function delete($params)
	{
		try {
			if (!isset($params['id']) || !is_numeric($params['id'])) {
				throw new Error("Identity of exchange is not a valid number", 400);
			}
			$id = $params['id'];
			$this->exchange->delete($id);
			$this->response->send(["exchanges" => "Exchange deleted"]);
		} catch (Error $err) {
			$this->response->send(["error" => $err->getMessage()], $err->getCode());
		}
	}
}
