<?php

namespace api\Models;

use base\models\Model;
use Error;

class Parameter extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get()
	{
		$query = "SELECT semilla as seed,
			nombre as name,
			rif as document,
			direccion as address,
			poblacion as zone,
			telefono as phone, 
			lapso as period, 
			lapsosiguiente as next_period
			FROM parametros";

		$data = parent::query($query);

		return $data;
	}
	function create($parameter)
	{
		if (!isset($parameter['seed']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la semilla");
		if (!isset($parameter['name']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el nombre");
		if (!isset($parameter['document']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el RIF");
		if (!isset($parameter['address']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la dirección");
		if (!isset($parameter['zone']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la población");
		if (!isset($parameter['phone']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el teléfono");
		if (!isset($parameter['period']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el lapso");
		if (!isset($parameter['next_period']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el lapso siguiente");

		$query = "INSERT INTO parametros(semilla, nombre, rif, direccion, poblacion, telefono, lapso, lapsosiguiente) 
			VALUES( :seed, :name, :document, :address, :zone, :phone, :period, :next_period)";

		$params = [
			'seed' => $parameter['seed'],
			'name' => $parameter['name'],
			'document' => $parameter['document'],
			'address' => $parameter['address'],
			'zone' => $parameter['zone'],
			'phone' => $parameter['phone'],
			'period' => $parameter['period'],
			'next_period' => $parameter['next_period']
		];
		$data = parent::query($query, $params);
		return $data;
	}

	function update($parameter)
	{
		if (!isset($parameter['seed']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la semilla");
		if (!isset($parameter['name']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el nombre");
		if (!isset($parameter['document']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el RIF");
		if (!isset($parameter['address']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la dirección");
		if (!isset($parameter['zone']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la población");
		if (!isset($parameter['phone']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el teléfono");
		if (!isset($parameter['period']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el lapso");
		if (!isset($parameter['next_period']) || empty($parameter['seed']))
			throw new Error("No se ha especificado el lapso siguiente");

		$query = "UPDATE parametros SET 
			semilla = :seed,
			nombre = :name,
			rif = :document,
			direccion = :address,
			poblacion = :zone,
			telefono = :phone,
			lapso = :period,
			lapsosiguiente = :next_period
			where semilla = :seed";

		$params = [
			'seed' => $parameter['seed'],
			'name' => $parameter['name'],
			'document' => $parameter['document'],
			'address' => $parameter['address'],
			'zone' => $parameter['zone'],
			'phone' => $parameter['phone'],
			'period' => $parameter['period'],
			'next_period' => $parameter['next_period']
		];
		$data = parent::query($query, $params);
		return $data;
	}
	
	function delete($parameter)
	{
		if (!isset($parameter['seed']) || empty($parameter['seed']))
			throw new Error("No se ha especificado la semilla");
		$query = "DELETE FROM parametros WHERE semilla = :seed";
		$params = [
			'seed' => $parameter['seed']
		];
		$data = parent::query($query, $params);
		return $data;
	}
}
