<?php

namespace api\Models;

use base\models\Model;
use Error;

class Report extends Model
{
	/**
	 * Get reports for a dataset
	 */
	public function get($queryParams)
	{
		$params = [];
		$query = "select
			nombrecategoria as category,
			tipodepago as payment_method,
			Sum(monto) as amount
		from
			(
			select
				tipopago,
				case
					when tipopago = 'Tarjeta' then tipopago || ': ' || idtipopago
					else tipopago
				end as tipodepago,
				idcategori,
				pagos.monto,
				case
					when tipopago = 'Vaucher' then fecha
					else fechapago
				end as fechafinal,
				Nombrecategoria
			from
				Categorias
			inner join (tipospago
			inner join (Emisiones
			inner join pagos on
				Emisiones.idregistro = pagos.idregistr) on
				tipospago.idtipopago = pagos.idtipopag) on
				Categorias.idcategoria = Emisiones.idcategori
			where
				anulado = :canceled) as ooo
		where
			idcategori in (:categories)
			and tipopago in (:paymentMethods)
			and fechafinal >= :startDate
			and fechafinal <= :endDate
		group by
			tipodepago,
			nombrecategoria
		order by
			tipodepago,
			nombrecategoria;";

		list($params, $query) = $this->mapParams($queryParams, $query);

		$data = $this->query($query, $params, false);
		if (count($data)  <= 0)
			throw new Error("data not found", 404);

		//convert $data['amount'] field to float
		foreach ($data as $key => $value) {
			$data[$key]['amount'] = floatval($value['amount']);
		}

		// if all it's okay return the reports.
		return $data;
	}
	/* 
	* Get Interval of an report
	*/
	public function getReceiptInterval($queryParams)
	{
		$params = [];
		$query = "select
			distinct factura as receipt
		from
			tipospago,
			pagos,
			emisiones
		where
			idtipopago = idtipopag
			and idregistro = idregistr
			and idcategori in (:categories)
			and tipopago in (:paymentMethods)
			and fechapago >= :startDate
			and fechapago <= :endDate
			and anulado = :canceled
		order by
			1;";
		list($params, $query) = $this->mapParams($queryParams, $query);
		$data = $this->query($query, $params, false);

		return $data;
	}
	/**
	 * Get Interval of Chargers for a report
	 */
	public function getChargeInterval($queryParams)
	{
		$params = [];
		$query = "select
			idregistr as charge
		from
			tipospago,
			pagos,
			emisiones
		where
			idtipopago = idtipopag
			and idregistro = idregistr
			and tipopago in (:paymentMethods)
			and idcategori in (:categories)
			and fechapago >= :startDate
			and fechapago <= :endDate
			and anulado = :canceled
			order by idregistr;";
		list($params, $query) = $this->mapParams($queryParams, $query);
		$data = $this->query($query, $params, false);

		return $data;
	}
	/**
	 * Map query params for all class methods
	 */
	private function mapParams($queryParams, $query)
	{
		$params = [];
		//Map categories
		if (!empty($queryParams["categories"])) {
			$query = preg_replace("/:categories/", $queryParams["categories"], $query);
		} else {
			// traer todas las categorias
			$categories = new Category;
			$categories = $categories->getAll($params);
			$query = preg_replace("/:categories/", implode(", ", array_column($categories, "id")), $query);
		}

		//Map payment methods
		if (!empty($queryParams["paymentMethods"])) {
			$paymentMethods = "'" . implode("','", explode(",", $queryParams["paymentMethods"])) . "'";
			$query = preg_replace("/:paymentMethods/", $paymentMethods, $query);
		} else {
			// traer todos los tipos de pago
			$paymentMethods = new PaymentMethod;
			$paymentMethods = $paymentMethods->getAll($params);
			$query = preg_replace("/:paymentMethods/", "'" . implode("', '", array_column($paymentMethods, "tipopago")) . "'", $query);
		}

		//Map start date
		if (!empty($queryParams["startDate"])) {
			$params["startDate"] = $queryParams["startDate"];
		} else {
			// traer todos los tipos de pago
			$params["startDate"] = "1900-01-01";
		}

		//Map end date
		if (!empty($queryParams["endDate"])) {
			$params["endDate"] = $queryParams["endDate"];
		} else {
			// traer todos los tipos de pago
			$params["endDate"] = "2100-01-01";
		}

		if (!empty($queryParams["canceled"])) {
			$params["canceled"] = $queryParams["canceled"];
		} else {
			$params["canceled"] = false;
		}
		return [$params, $query];
	}
}
