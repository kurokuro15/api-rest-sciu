<?php

namespace api\Models;

use base\models\Model;
use Error;

class Exchange extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get($id)
	{
		if (!isset($id) || !is_numeric($id)) {
			throw new Error("Identity of exchange is not a valid number", 400);
		}
		$param = [":id" => $id];
		$query = "select * from bolivar_exchange where id = :id;";
		$data = parent::queryAuth($query, $param);

		if (count($data) <= 0)
			throw new Error("data not found", 404);
		return $data[0];
	}

	function getLast()
	{
		$query = "select dolar, euro, date from bolivar_exchange order by date desc limit 1;";

		$data = parent::queryAuth($query);

		if (is_array($data)) {
			foreach ($data[0] as $prop => $value) {
				$this->$prop = $value;
			}
		}

		return $data[0];
	}

	function getAll($params)
	{
		$query = "select * from bolivar_exchange";
		$pages = $this->pagination($params);
		$param = [];
		if (count($pages) > 0) {
			$pages["count"] = $this->countAuth($query);
			$query .= $pages['placeholder'];
			$param = $pages['current'];
			$this->pages = $pages;
		}
		$data = parent::queryAuth($query, $param);
		return $data;
	}

	function update($exchange)
	{
		if (empty($exchange)) {
			throw new Error("invalid request body", 400);
		}
		if (empty($exchange["id"])) {
			throw new Error("not id field defined", 400);
		}
		if (empty($exchange["dolar"])) {
			throw new Error("not dolar field defined", 400);
		}
		if (empty($exchange["euro"])) {
			throw new Error("not dolar field defined", 400);
		}
		$today = date("c");
		$query = "update bolivar_exchange set dolar = :dolar, euro = :euro, date = :date where id = :id returning id;";
		$param = [
			":date" => $today,
			":dolar" => $exchange['dolar'],
			":euro" => $exchange['euro'],
			":id" => $exchange["id"],
		];
		$result = parent::queryAuth($query, $param);
		if (count($result)  <= 0)
			throw new Error("data not update", 404);
		return $result[0]["id"];
	}

	function insert($exchange)
	{
		$this->getLast();
		$query = "insert into bolivar_exchange (dolar, euro, date) values (:dolar, :euro, :date) returning id;";
		// equal to supabase date... 
		$param = ["date" => date("c")];
		if (empty($exchange['dolar']) && empty($exchange['euro']))
			throw new Error("dolar and euro are required", 400);
		if (empty($exchange['dolar']))
			$param['dolar'] = $this->dolar;
		else
			$param['dolar'] = $exchange['dolar'];
		if (empty($exchange['euro']))
			$param['euro'] = $this->euro;
		else
			$param['euro'] = $exchange['euro'];

		$result = parent::queryAuth($query, $param);
		if (count($result)  <= 0)
			throw new Error("Exchange not created", 404);
		return $result[0]["id"];
	}

	function delete($id)
	{
		if (empty($id))
			throw new Error("Identity of exchanges is not a valid number", 400);
		$param = [":id" => $id];
		$query = "delete from bolivar_exchange where id = :id";
		$result = parent::queryAuth($query, $param);
		return $result;
	}
}
