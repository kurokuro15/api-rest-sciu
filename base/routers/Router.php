<?php
namespace base\routers;
/**
 * Este archivo llevará la clase Router, quien contendrá todas las rutas posibles y los métodos de estas. 
 */
class Router {
	/**
	 * route list.
	 */
	private $router = [];
	/**
	 * match route list.
	 */
	private $matchRouter = [];
	/**
	 * match pattern route list.
	 */
	private $matchPatternRouter = [];
	/**
	 * request url.
	 */
	private $url;
	/**
	 * request http method.
	 */
	private $method;
	/**
	 * param list for route pattern
	 */
	private $params = [];
	/**
	 *  Response Class
	 */
	private $response;

	function __construct($url, $method)
	{
		// Home es la sub-ruta posterior al dominio
		$home = "/api";
		// url : /api/estudiantes/:cedula/factura/:factura
		$this->url = str_replace($home, "", $url);
		// this->url : /estudiantes/:cedula/factura/:factura
		$this->method = $method;

		// obtenemos la clase de response. 
		// $this->response = $GLOBALS['response'];
	}
	/**
	 *  Añade una instancia de la clase Route al enrutador
	 */
	public function addRoute($method, $pattern, $callback)
	{
		array_push($this->router, new Route($method, $pattern, $callback));
	}

	/**
	 *  set get request http method for route
	 */
	public function get($pattern, $callback)
	{
		$this->addRoute("GET", $pattern, $callback);
	}

	/**
	 *  set post request http method for route
	 */
	public function post($pattern, $callback)
	{
		$this->addRoute('POST', $pattern, $callback);
	}

	/**
	 *  set put request http method for route
	 */
	public function put($pattern, $callback)
	{
		$this->addRoute('PUT', $pattern, $callback);
	}

	/**
	 *  set delete request http method for route
	 */
	public function delete($pattern, $callback)
	{
		$this->addRoute('DELETE', $pattern, $callback);
	}

	/**
	 *  dispatch url and pattern
	 */
	public function dispatch($url, $pattern)
	{
		preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

		$patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

		if (substr($pattern, -1) === '/') {
			$patternAsRegex = $patternAsRegex . '?';
		}
		$patternAsRegex = '@^' . $patternAsRegex . '$@';

		// check match request url
		if (preg_match($patternAsRegex, $url, $paramsValue)) {
			array_shift($paramsValue);
			foreach ($params[0] as $key => $value) {
				$val = substr($value, 1);
				if ($paramsValue[$val]) {
					$this->setParams($val, urlencode($paramsValue[$val]));
				}
			}

			return true;
		}

		return false;
	}

	/**
	 *  get router
	 */
	public function getRouter()
	{
		return $this->router;
	}

	/**
	 *  run application
	 */
	public function run()
	{
		if (!is_array($this->router) || empty($this->router))
			throw new \Exception('NON-Object Route Set');
		// filtramos por método las rutas que coincidan
		$this->getMatchRoutersByRequestMethod();
		//filtramos por endpoint y preparamos TODAS las rutas que coincidan.
		$this->getMatchRoutersByPattern($this->params);

		//Validamos que no esté vacío el arreglo de rutas de coincidencias
		if (!$this->matchPatternRouter || empty($this->matchPatternRouter)) {
			// $this->sendNotFound();
			print_r('Error, no hay resultados');
		} else {
			// Hacemos la llamada del callback a cada método de cada ruta
			foreach ($this->matchPatternRouter as $route) {
				if (is_callable($route->getCallback())) {
					call_user_func($route->getCallback(), $this->params);
				} else {
					throw new \Exception("error");
				}
			}
		}
	}

	/**
	 * set param
	 */
	private function setParams($key, $value)
	{
		$this->params[$key] = $value;
	}

	/**
	 *  filter requests by http method
	 */
	private function getMatchRoutersByRequestMethod()
	{
		foreach ($this->router as $value) {
			if (strtoupper($this->method) === $value->getMethod())
				array_push($this->matchRouter, $value);
		}
	}

	/**
	 * filter route patterns by url request with recursive
	 */
	private function getMatchRoutersByPattern(&$params)
	{
		$url = $this->url;
		$routes = $this->matchRouter;
		// validamos si la url empieza por '/' (es la primera pasada) o no.
		if (strpos($url, "/") === 0) {
			$url_segments = explode("/", $url, 4);
		} else {
			$url_segments = explode("/", $url, 3);
		}
		// quito la primera posicion sí viene vacia
		if (empty($url_segments[0]))
			array_shift($url_segments);
		//iteramos cada ruta matcheada anteriormente
		foreach ($routes as $k => $route) {
			$pattern_segments = $route->getPatternSegments();

			// averiguamos si es el mismo EP
			// si el primer segmento no es igual, nos vamos a la siguiente ruta
			if ($url_segments[0] !== $pattern_segments[0]) {
				continue;
			}
			// si el pattern no tiene un parámetro y la url sí, sigamos pa lante
			if ((empty($pattern_segments[1]) && !empty($url_segments[1])) || 
					(!empty($pattern_segments[1]) && empty($url_segments[1]))) {
						array_push($this->matchPatternRouter, $route);
				continue;
			}

			// si existe un segundo parametro y no es un int(esto debemos permitir definirlo en el pattern) valido, 
			// caso contrario retornamos error
			if (!empty($url_segments[1]) && (int) ($url_segments[1]) === 0) {
				echo "Parametro no valido";
				return;
			}

			// si no hay parámetro, se detiene la iteración y setea matchRouter con la ruta actual.
			if (empty($url_segments[1]) && empty($pattern_segments[1])) {
				array_push($this->matchPatternRouter, $route);
				break;
			}

			// Si está definida la posición 1 del url y del pattern, siendo esta un int válido, 
			// entonces mapeamos y guardamos en params
			// obtener el nombre del param
			$param_key = ltrim($pattern_segments[1], ":");
			// definir el parametro
			$this->setParams($param_key, (int) ($url_segments[1]));

			// si no siguen mas segmentos en la url, llama al callback de la ruta actual porque ya esta es la última.
			// caso contrario, volvamos a picar la ruta
			if (isset($url_segments[2])) {
				echo "llámame a este:" . "<br>" . $route->getPattern() . "<br>";
				print_r($this->params);
				echo "<br>";
				array_push($this->matchPatternRouter, $route);
				//$callback = $pattern->callback($params);
				$this->url = $url_segments[2];
				$this->getMatchRoutersByPattern($this->params);
			} else {
				echo "LLamame a este mismo:" . "<br>" . $route->getPattern() . "<br>";
				print_r($params);
				array_push($this->matchPatternRouter, $route);
				break;
			}
			// repetir de nuevo
		}
	}

	private function sendNotFound()
	{
		$this->response->sendStatus(404);
		$this->response->setContent(['error' => 'Sorry This Route Not Found !', 'status_code' => 404]);
	}
}
