<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

abstract class AbstractCollection implements Collection {

	protected $items = [];

	public function add(CollectionItem $item) {
		$this->items[$item->getId()] = $item;

		return $this;
	}

	public function addAll(array /*of CollectionItem*/
	                       $items) {
		foreach ($items as $item) {
			$this->items[$item->getId()] = $item;
		}

		return $this;
	}

	public function clear() {
		$this->items = [];

		return $this;
	}

	public function contains(CollectionItem $item) {
		return isset($this->items[$item->getId()]);
	}

	public function containsAll(array /*of CollectionItem*/
	                            $items) {
		return (array_intersect($items, $this->items) == $items);
	}

	public function isEmpty() {
		return (count($this->items) == 0);
	}

	public function size() {
		return count($this->items);
	}

	public function remove(CollectionItem $item) {
		if (isset($this->items[$item->getId()])) {
			unset($this->items[$item->getId()]);
		}

		return $this;
	}

	public function removeAll(array /*of CollectionItem*/
	                          $items) {
		$this->items = array_diff($this->items, $items);

		return $this;
	}

	public function retainAll(array /*of CollectionItem*/
	                          $items) {
		$this->items = $items;

		return $this;
	}

	public function getList() {
		return $this->items;
	}

	/**
	 * @return CollectionItem
	 **/
	public function getByName($name) {
		return $this->items[$name];
	}

	public function has($name) {
		return isset($this->items[$name]);
	}
}
