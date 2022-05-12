<?php
namespace api\Models;
use \base\Models\Model;
/**
 * Class Model of collection orders. 
 */
class Order extends Model {
	function __constructor(){
		parent::__constructor();

	}
	/**
	 * Retrieve from Order or 'emisiones' table.
	 */
	function get($registro) {
				//Validate param
				if (!isset($registro) || (int) $registro === 0) {
					throw new ValueError("collection order number is not a valid number", 401);
				}
				// map param in a array
				$param = [":registro" => $registro];
				// prepare query
				$query = "SELECT idregistro as id, fechaemision as reg_date, concepto as concept, monto as amount, id_cedul as cedula, unidades, nombrecategoria as category, nombreproducto  as product FROM emisiones JOIN categorias ON idcategori = idcategoria JOIN productos ON idproduct = idproducto WHERE id = :registro;";
				$data = parent::query($query, $param);
				if (is_array($data)) {
					// we map properties of class to use this info. And send Json object form return.
					foreach ($data[0] as $prop => $value) {
						$this->$prop = $value;
					}
				} else {
					throw new Error("data not found", 404);
				}
				// if all it's okay return the order.
				return $data[0];
			}
			function getByStudent($cedula) {
				//Validate param
				if (!isset($cedula) || (int) $cedula === 0) {
					throw new ValueError("Cedula number is not a valid number", 401);
				}
				// map param in a array
				$param = [":cedula" => $cedula];
				// prepare query
				// falta filtrar por pagados maybe with a LEFT JOIN or RIGHT JOINs
				$query = "SELECT idregistro as id, fechaemision as reg_date, concepto as concept, monto as amount, id_cedul as cedula, unidades, nombrecategoria as category, nombreproducto  as product FROM emisiones JOIN categorias ON idcategori = idcategoria JOIN productos ON idproduct = idproducto WHERE cedula = :cedula ORDER BY reg_date desc, id desc;";
				$data = parent::query($query, $param);
				if (is_array($data)) {
					// we map properties of class to use this info. And send Json object form return.
					foreach ($data[0] as $prop => $value) {
						$this->$prop = $value;
					}
				} else {
					throw new Error("data not found", 404);
				}
				// if all it's okay return the order.
				return $data[0];
			}
			function getAll($params){
				$pagination = parent::pagination($params);
				// hacemos magiaaaaaaa :V
			}
		}