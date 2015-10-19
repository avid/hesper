<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Victor V. Bolshov
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Form\Form;

/**
 * Wrapper around given childs of LogicalObject with custom logic-glue's.
 * @package Hesper\Core\Logic
 */
class CallbackLogicalObject implements LogicalObject {

	/**
	 * @var \Closure
	 */
	private $callback = null;

	/**
	 * @static
	 *
	 * @param \Closure $callback
	 *
	 * @return CallbackLogicalObject
	 */
	static public function create($callback) {
		return new static($callback);
	}

	/**
	 * @param \Closure $callback
	 */
	public function __construct($callback) {
		Assert::isTrue(is_callable($callback, true), 'callback must be callable');
		$this->callback = $callback;
	}

	/**
	 * @param Form $form
	 *
	 * @return bool
	 */
	public function toBoolean(Form $form) {
		return call_user_func($this->callback, $form);
	}

	/**
	 * @param Dialect $dialect
	 *
	 * @throws UnimplementedFeatureException
	 */
	public function toDialectString(Dialect $dialect) {
		throw new UnimplementedFeatureException("toDialectString is not needed here");
	}
}
