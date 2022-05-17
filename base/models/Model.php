<?php

namespace base\models;

use Error;
use Exception;
use \PDO;
use \PDOException;
use ValueError;

class Model
{
	private $conData;
	protected $conection;
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

		$PSQL_DNS = "pgsql:
            host={$this->conData["host"]};
            port={$this->conData["port"]};
            dbname={$this->conData["database"]};
						user={$this->conData["user"]};
						password={$this->conData["password"]};";
		// intenta crear la conection a la BD con los datos
		// // o muestra el error si ocurre alguno
		try {
			$this->conection = new PDO($PSQL_DNS, $this->conData["user"]);
		} catch (PDOException $err) {
			echo "{$err->getMessage()}";
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
	public function query(string $query, $params = [], $format=true)
	{
		$stmt = $this->conection->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$type = $this->types[gettype($value)];
			if (!$stmt->bindValue($param, $value, $type)) {
				throw new Error("Can't bind param: {$param} with value: {$value}");
			};
		}

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				if($format){
					return $this->toUTF8($result);
				} 
				return $result;
			}
		}
		throw new ValueError("not found",200);
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
				throw new Error("Can't bind param: {$param} with value: {$value}");
			};
		}

		if ($stmt->execute()) {
			return $this->conection->lastInsertId();
		}

		throw new ValueError("not changed",200);
	}

	private function getConfigFile($file)
	{
		$root = dirname(dirname(__DIR__));
		$json = file_get_contents($root . "\Config/" . $file);
		return json_decode($json, true);
	}

	/**
	 * take the number of page and number of items to show for page
	 * by default page 0 and 20 items.
	 */
	protected function pagination($params)
	{
		$page = 0;
		$records = 10;

		if (isset($params['page'])) {
			$page = $params['page'];
		}
		if (isset($params['records'])) {
			$records = $params['records'];
		}

		$registroInicial = ($records * ($page));

		if ($page > 1) {
		}
		// limit determina la cantidad de items
		return ["inited" => $registroInicial, "records" => $records];
		// offset determina el index desde el cual contar (empieza en 0)
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
}
