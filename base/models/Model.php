<?php

namespace base\models;

use Exception;
use PDO;
use PDOException;
use Error;

class Model
{
	private $conData;
	protected $conection;
	protected $authentication;
	protected $types = array(
		"boolean" => PDO::PARAM_BOOL,
		"integer" => PDO::PARAM_INT,
		"double" => PDO::PARAM_STR,
		"string" => PDO::PARAM_STR,
		"array" => PDO::PARAM_STR,
		"object" => PDO::PARAM_STR,
		"resource" => PDO::PARAM_STR,
		"NULL" => PDO::PARAM_NULL,
		"unknown type" => PDO::PARAM_NULL
	);
	function __construct()
	{
		// obtener datos de la conection desde el archivo config
		$this->conData = $this->getConfigFile(".env");

		// // transformarlos a un array map
		$this->conData = $this->conData["conection"];

		// string con la informacion requerida para conectar a la BD
		// $MYSQL_DSN = "mysql:
		//         host={$this->conData["host"]};
		//         port={$this->conData["port"]};
		//         dbname={$this->conData["database"]};
		//         charset=utf8";

		// Conexión PDO a la base de datos de Caja
		$PSQL_DNS = "pgsql:
			host={$this->conData["host"]};
			port={$this->conData["port"]};
			dbname={$this->conData["database"]};
			user={$this->conData["user"]};
			password={$this->conData["password"]};";
		// Conexión PDO a la base de datos de autenticación
		$PSQL_AUTH_DNS = "pgsql:
			host={$this->conData["host"]};
			port={$this->conData["port"]};
			dbname={$this->conData["databaseAuth"]};
			user={$this->conData["user"]};
			password={$this->conData["password"]};";
		// intenta crear la conection a la BD con los datos
		// // o muestra el error si ocurre alguno
		try {
			// Conexión PDO a la base de datos de Caja
			$this->conection = new PDO($PSQL_DNS, $this->conData["user"], null, array(
				PDO::ATTR_PERSISTENT => true
			));
			// Conexión PDO a la base de datos de autenticación
			$this->authentication = new PDO($PSQL_AUTH_DNS, $this->conData["user"], null, array(
				PDO::ATTR_PERSISTENT => true
			));
		} catch (PDOException $err) {
			throw new Error($err->getMessage(), 500);
		}
	}

	/** 
	 * metodo base para obtener filas de la BD
	 * recibe un array de arrays donde cada uno es un
	 * array map de la forma
	 * [param => value]
	 * donde nombre es el placeholder en la query y tipo es una de las constantes
	 * PDO::PARAM_*
	 * */
	public function query(string $query, $params = [], $format = true)
	{
		$stmt = $this->conection->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$type = $this->types[gettype($value)];
			if (!$stmt->bindValue($param, $value, $type)) {
				throw new Error("Can't bind param: {$param} with value: {$value}", 500);
			};
		}

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (isset($result)) {
				if ($format) {
					return $this->toUTF8($result) ?: array();
				}
				return $result ?: array();
			}
		}
		throw new Error(json_encode($stmt->errorInfo(), JSON_UNESCAPED_UNICODE), 500);
	}

	/** 
	 * metodo base para non-queries
	 * recibe un array de la forma:
	 * [param => value]
	 * donde nombre es el placeholder en la query y tipo es una de las constantes
	 * PDO::PARAM_*
	 * */
	function nonQuery(string $query, $params = [])
	{
		$stmt = $this->conection->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$type = $this->types[gettype($value)];
			if (!$stmt->bindValue($param, $value, $type)) {
				throw new Error("Can't bind param: {$param} with value: {$value}", 500);
			};
		}

		if ($stmt->execute()) {
			return $this->conection->lastInsertId();
		}

		throw new Error(json_encode($stmt->errorInfo(), JSON_UNESCAPED_UNICODE), 500);
	}

	private function getConfigFile($file)
	{
		$root = dirname(dirname(__DIR__));
		$json = file_get_contents($root . "/config/" . $file);
		return json_decode($json, true);
	}

	/** 
	 * metodo base para obtener filas de la BD de autenticación
	 * recibe un array de arrays donde cada uno es un
	 * array map de la forma
	 * [param => value]
	 * donde nombre es el placeholder en la query y tipo es una de las constantes
	 * PDO::PARAM_*
	 * */
	public function queryAuth(string $query, $params = [])
	{
		$stmt = $this->authentication->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$type = $this->types[gettype($value)];
			if (!$stmt->bindValue($param, $value, $type)) {
				throw new Error("Can't bind param: {$param} with value: {$value}", 500);
			};
		}

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (isset($result)) {
				return $result ?: array();
			}
		}
		throw new Error(json_encode($stmt->errorInfo(), JSON_UNESCAPED_UNICODE), 500);
	}

	/**
	 * take the number of page and number of items to show for page
	 * by default page 0 and 10 items. array with keys: "offset" and "limit"
	 */
	protected function pagination($params, $strict = true)
	{
		if ((!isset($params['offset']) || !isset($params['limit'])) && !$strict)
			return Array();

		if (isset($params['offset'])) {
			$offset = intval($params['offset']);
		} else {
			$offset = 0;
		}
		if (isset($params['limit'])) {
			$limit = intval($params['limit']);
		} else {
			$limit = 10;
		}

		$current = [
			"offset" => $offset,
			"limit" => $limit
		];
		$placeholder =  " OFFSET :offset LIMIT :limit";
		$next = [
			"offset" => $offset + $limit,
			"limit" => $limit
		];

		$prevOffset = $offset - $limit;
		if ($offset === 0) {
			$prevOffset = null;
		} else if ($prevOffset < 0) {
			$prevOffset = 0;
			$limit = $offset;
		}

		$prev = [
			"offset" => $prevOffset,
			"limit" => $limit
		];
		// devolvemos los parametros y el placeholder
		return ["current" => $current, "placeholder" => $placeholder,"next" => $next, "prev" => $prev];
	}

	private function toUTF8($array)
	{
		try {
			array_walk_recursive($array, function (&$item, $key) {
				if (!mb_detect_encoding($item, 'utf-8', true)) {
					$item = utf8_encode($item);
				}
			});
			return $array;
		} catch (Exception $err) {
		}
	}
	/**
	 * validate blank fields
	 */
	protected function is_blank($value)
	{
		return empty($value) && !is_numeric($value);
	}

	protected function count($query)
	{
		$stmt = $this->conection->prepare($query);
		$stmt->execute();
		return $stmt->rowCount();
	}
	protected function countAuth($query)
	{
		$stmt = $this->authentication->prepare($query);
		$stmt->execute();
		return $stmt->rowCount();
	}
}
