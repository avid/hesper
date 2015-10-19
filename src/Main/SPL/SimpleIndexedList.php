<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Main\SPL;

class SimpleIndexedList extends AbstractList {

	public function offsetSet($offset, $value) {
		$this->list[$offset] = $value;

		return $this;
	}

}