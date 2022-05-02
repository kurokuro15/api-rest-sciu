<?php

class Model
{
	private $conData;
	protected $conection;

	function __construct()
	{
		// obtener datos de la conection desde el archivo config
		$this->conData = $this->getFileData(".env");
		// transformarlos a un array map
		$this->conData = $this->conData["conection"];

		// string con la informacion requerida para conectar a la BD
		$MYSQL_DSN = "mysql:
            host={$this->conData["host"]};
            port={$this->conData["port"]};
            dbname={$this->conData["database"]};
            charset=utf8";

		$PSQL_DNS = "pgsql:
            host={$this->conData["host"]};
            port={$this->conData["port"]};
            dbname={$this->conData["database"]};
						user={$this->conData["user"]};
						password={$this->conData["password"]};";

		// intenta crear la conection a la BD con los datos
		// o muestra el error si ocurre alguno
		try {
			$this->conection = new PDO($PSQL_DNS, $this->conData["user"]);
		} catch (PDOException $err) {
			echo "Error: {$err->getMessage()}";
		}
	}

	// metodo base para obtener filas de la BD
	// recibe un array de arrays donde cada uno es un
	// array map de la forma
	// [param => value]
	// donde nombre es el placeholder en la query y tipo es una de las constantes
	// PDO::PARAM_*
	public function query(string $query, $params = [])
	{
		$stmt = $this->conection->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$stmt->bindParam($param, $value);
		}

		if ($stmt->execute()) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				return $result;
			}
		}

		return false;
	}

	// metodo base para realizar inserts
	function nonQuery(string $query, $params = [])
	{
		$stmt = $this->conection->prepare($query);

		// bindear los parametros dados a la query
		foreach ($params as $param => $value) {
			$stmt->bindParam($param, $value);
		}

		if ($stmt->execute()) {
			return $this->conection->lastInsertId();
		}

		return false;
	}

	private function getFileData($file = "config"){
		$dir = dirname(__FILE__);
		$json = file_get_contents($dir . "/" . $file);
		return json_decode($json, true);
	}
}
