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
 * Interface RouterRule
 * @package Hesper\Main\Util\Router
 */
interface RouterRule {

	/**
	 * Matches a user submitted path with parts defined by a map.
	 * Assigns and returns an array of variables on a successful match.
	 * @return array An array of assigned values or empty array() on a mismatch
	 **/
	public function match(HttpRequest $request);

	public function assembly(array $data = [], $reset = false, $encode = false);
}
