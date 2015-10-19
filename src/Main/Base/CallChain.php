<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Garmonbozia Research Group
 */
namespace Hesper\Main\Base;

use Hesper\Core\Exception\WrongStateException;

/**
 * @ingroup Helpers
 **/
final class CallChain {

	private $chain = [];

	/**
	 * @return CallChain
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return CallChain
	 **/
	public function add($object) {
		$this->chain[] = $object;

		return $this;
	}

	public function call($method, $args = null /* , ... */) {
		if (!$this->chain) {
			throw new WrongStateException();
		}

		$args = func_get_args();
		array_shift($args);

		if (count($args)) {
			$result = $args;
			foreach ($this->chain as $object) {
				$result = call_user_func_array([$object, $method], is_array($result) ? $result : [$result]);
			}
		} else {
			foreach ($this->chain as $object) {
				$result = call_user_func([$object, $method]);
			}
		}

		return $result;
	}

	public function __call($method, $args = null) {
		return call_user_func_array([$this, 'call'], array_merge([$method], $args));
	}
}
