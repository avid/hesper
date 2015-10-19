<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\Router;

use Hesper\Main\Flow\HttpRequest;

/**
 * Class RouterChainRule
 * @package Hesper\Main\Util\Router
 */
final class RouterChainRule extends RouterBaseRule {

	protected $routes     = [];
	protected $separators = [];

	/**
	 * @return RouterChainRule
	 **/
	public static function create() {
		return new self();
	}

	/**
	 * @return RouterChainRule
	 **/
	public function chain(RouterRule $route, $separator = '/') {
		$this->routes[] = $route;
		$this->separators[] = $separator;

		return $this;
	}

	public function getCount() {
		return count($this->routes);
	}

	public function match(HttpRequest $request) {
		$values = [];

		foreach ($this->routes as $key => $route) {
			$res = $route->match($request);

			if (empty($res)) {
				return [];
			}

			$values = $res + $values;
		}

		return $values;
	}

	public function assembly(array $data = [], $reset = false, $encode = false) {
		$value = null;

		foreach ($this->routes as $key => $route) {
			if ($key > 0) {
				$value .= $this->separators[$key];
			}

			$value .= $route->assembly($data, $reset, $encode);

			if ($route instanceof RouterHostnameRule && $key > 0) {
				throw new RouterException('wrong chain route');
			}
		}

		return $value;
	}
}
