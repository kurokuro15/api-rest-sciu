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
	 *  Construct
	 */
	public function __construct()
	{
		$this->request = $GLOBALS['request'];
		$this->response = $GLOBALS['response'];
	}

	protected function getMeta($meta)
	{
		if (isset($meta)) {
			$url = $this->request->getUrl();
			if ($meta["next"]["offset"] <= $meta["count"])
				$meta["next"] = $url . "?offset=" . $meta["next"]["offset"] . "&limit=" . $meta["next"]["limit"];
			else
				$meta["next"] = null;


			if ($meta["prev"]["offset"] >= 0)
				$meta["prev"] = $url . "?offset=" . $meta["prev"]["offset"] . "&limit=" . $meta["prev"]["limit"];
			else
				$meta["prev"] = null;
		}

		$this->response->send(["meta" => $meta]);
	}
}
