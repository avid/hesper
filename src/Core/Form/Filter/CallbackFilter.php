<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexey S. Denisov
 */
namespace Hesper\Core\Form\Filter;

/**
 * @ingroup Filters
 **/
final class CallbackFilter implements Filtrator {

	/**
	 * @var \Closure
	 */
	private $callback = null;

	/**
	 * @return CallbackFilter
	 **/
	public static function create(\Closure $callback) {
		return new self($callback);
	}

	public function __construct(\Closure $callback) {
		$this->callback = $callback;
	}

	public function apply($value) {
		return $this->callback->__invoke($value);
	}
}
