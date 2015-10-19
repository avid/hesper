<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\SPL;

use Hesper\Core\Exception\MissingElementException;

/**
 * Base for handling Identifiable object's lists.
 * @package Hesper\Main\SPL
 */
abstract class AbstractList implements \ArrayAccess, SimplifiedArrayAccess {

	protected $list = [];

	public function offsetGet($offset) {
		if (isset($this->list[$offset])) {
			return $this->list[$offset];
		}

		throw new MissingElementException("no object found with index == '{$offset}'");
	}

	/**
	 * @return AbstractList
	 **/
	public function offsetUnset($offset) {
		unset($this->list[$offset]);

		return $this;
	}

	public function offsetExists($offset) {
		return isset($this->list[$offset]);
	}

	// SAA goes here

	/**
	 * @return AbstractList
	 **/
	public function clean() {
		$this->list = [];

		return $this;
	}

	public function isEmpty() {
		return ($this->list === []);
	}

	public function getList() {
		return $this->list;
	}

	public function set($name, $var) {
		return $this->offsetSet($name, $var);
	}

	public function get($name) {
		return $this->offsetGet($name);
	}

	public function has($name) {
		return $this->offsetExists($name);
	}

	public function drop($name) {
		return $this->offsetUnset($name);
	}
}
