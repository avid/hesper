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
 * Class RouterStaticRule
 * @package Hesper\Main\Util\Router
 */
final class RouterStaticRule extends RouterBaseRule {

	protected $route = null;

	/**
	 * @return RouterStaticRule
	 **/
	public static function create($route) {
		return new self($route);
	}

	public function __construct($route) {
		// FIXME: rtrim. probably?
		$this->route = trim($route, '/');
	}

	public function match(HttpRequest $request) {
		$path = $this->processPath($request)->toString();

		// FIXME: rtrim, probably?
		if (trim(urldecode($path), '/') == $this->route) {
			return $this->defaults;
		}

		return false;
	}

	public function assembly(array $data = [], $reset = false, $encode = false) {
		return $this->route;
	}
}
