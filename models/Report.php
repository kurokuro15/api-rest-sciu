<?php

namespace api\Models;

use base\models\Model;

class Report extends Model
{
	/**
	 * Get reports for a dataset
	 */
	public function getCashRegister($queryParams)
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
		//convert $data['amount'] field to float
		foreach ($data as $key => $value) {
			$data[$key]['amount'] = floatval($value['amount']);
		}

		// if all it's okay return the reports.
		return $data;
	}
	/**
	 * Get Detailed reports for a dataset
	 */
	public function getDetailed($queryParams)
	{
		$params = [];
		$query = "select 
				p.factura as receipt,	
				e.id_cedul as cedula, 
				t.tipopago as payment, 
				t.idtipopago as deposit,
				SUM(p.monto) as amount,
				p.anulado as canceled,
				t.fecha as payment_date,
				p.fechapago as reg_date
			from
				pagos p
			join 
				emisiones e on
				e.idregistro = p.idregistr
			join 
				tipospago t on
				t.idtipopago = p.idtipopag
			where
				tipopago in (:paymentMethods)
				and ((p.fechapago >= :startDate
					and p.fechapago <= :endDate)
				or (t.fecha >= :startDate
					and t.fecha <= :endDate))
			group by 
				payment,
				deposit,
				cedula,
				receipt,
				payment_date,
				reg_date,
				canceled
			order by 
				reg_date desc,
				payment_date desc,
				receipt desc,
				cedula desc";

		list($params, $query) = $this->mapParams($queryParams, $query, false);

		$data = $this->query($query, $params, false);
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
			and ((fechapago >= :startDate
			and fechapago <= :endDate) 
			or (fecha >= :startDate
			and fecha <= :endDate) )
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
			and ((fechapago >= :startDate
			and fechapago <= :endDate) 
			or (fecha >= :startDate
			and fecha <= :endDate) )
			and anulado = :canceled
			order by idregistr;";
		list($params, $query) = $this->mapParams($queryParams, $query);
		$data = $this->query($query, $params, false);

		return $data;
	}
	/**
	 * Map query params for all class methods
	 */
	private function mapParams($queryParams, $query, $filter_canceled = true)
	{
		$params = [];
		//Map categories
		if (empty($queryParams["categories"])) {
			// traer todas las categorias
			$categories = new Category;
			// por implementar (limpiar)
			list($categories) = $categories->getAll($params);
			$query = preg_replace("/:categories/", implode(", ", array_column($categories, "id")), $query);
		} else {
			$query = preg_replace("/:categories/", $queryParams["categories"], $query);
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

		if ($filter_canceled) {
			if (!empty($queryParams["canceled"])) {
				$params["canceled"] = $queryParams["canceled"];
			} else {
				$params["canceled"] = false;
			}
		}
		return [$params, $query];
	}
}
