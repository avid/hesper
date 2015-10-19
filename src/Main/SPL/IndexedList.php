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
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Unordered indexed list of Identifiable objects.
 * @package Hesper\Main\SPL
 */
final class IndexedList extends AbstractList {

	/**
	 * @return IndexedList
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return IndexedList
	 **/
	public function offsetSet($offset, $value) {
		Assert::isTrue($value instanceof Identifiable);

		$offset = $value->getId();

		if ($this->offsetExists($offset)) {
			throw new WrongArgumentException("object with id == '{$offset}' already exists");
		}

		$this->list[$offset] = $value;

		return $this;
	}
}
