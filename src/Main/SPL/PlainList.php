<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\SPL;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;

/**
 * Ordered unindexed list of Identifiable objects.
 * @package Hesper\Main\SPL
 */
final class PlainList extends AbstractList {

	/**
	 * @return PlainList
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return PlainList
	 **/
	public function offsetSet($offset, $value) {
		Assert::isTrue($value instanceof Identifiable);

		$this->list[] = $value;

		return $this;
	}
}
