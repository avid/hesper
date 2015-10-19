<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Artem Naumenko
 */
namespace Hesper\Core\Base;

/**
 * Interface Listable
 * @package Hesper\Core\Base
 */
interface Listable extends \Iterator, \ArrayAccess, \Countable, \SeekableIterator {

	/**
	 * Push new list item to the tail of list
	 * @return Listable
	 */
	public function append($value);

	/**
	 * Push new list item to the head of list
	 * @return Listable
	 */
	public function prepend($value);

	/**
	 * Trims $length items starting from @start
	 * @return Listable
	 */
	public function trim($start, $length);

	/**
	 * Truncates list
	 * @return Listable
	 */
	public function clear();

	/**
	 * @return mixed
	 */
	public function get($index);

	/**
	 * Returns the last element of list and removing it
	 * @return mixed
	 */
	public function pop();

	/**
	 * @return Listable
	 */
	public function set($index, $value);

	/**
	 * Returns sublist from $start to $start+$length
	 * @return array
	 */
	public function range($start, $length);
}
