<?

namespace base\routers;

/**
 * Este archivo llevará la clase Route quien tendrá la definición de una ruta.
 */
/**
 * Modelo de una ruta
 */

/**
 * @param $method string [GET,POST,PUT,DELETE]
 */
class Route
{
	private $method;
	private $pattern;
	private $callback;
	private $listMethod = ['GET', 'POST', 'PUT', 'DELETE'];

	function __construct($method, $pattern, $callback)
	{
		$this->method = $this->validateMethod($method);
		$this->pattern = $pattern;
		$this->callback = $callback;
	}
	public function getMethod()
	{
		return $this->method;
	}
	public function getPattern()
	{
		return $this->pattern;
	}
	public function getCallback()
	{
		return $this->callback;
	}

	private function validateMethod($method) {
		if (in_array(strtoupper($method), $this->listMethod)) 
				return $method;

		throw new \Exception('Invalid Method Name');
}

}
