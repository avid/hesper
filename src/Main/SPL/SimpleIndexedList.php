<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Main\SPL;

class SimpleIndexedList extends AbstractList {

	/**
	 * @return SimpleIndexedList
	 */
	public static function create() {
		return new self;
	}

	public function offsetSet($offset, $value) {
		$this->list[$offset] = $value;

		return $this;
	}

}