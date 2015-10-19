<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Filter;

/**
 * Class StringReplaceFilter
 * @package Hesper\Core\Form\Filter
 */
final class StringReplaceFilter implements Filtrator {

	private $search  = null;
	private $replace = null;

	private $count = null;

	/**
	 * @return StringReplaceFilter
	 **/
	public static function create($search = null, $replace = null) {
		return new self($search, $replace);
	}

	public function __construct($search = null, $replace = null) {
		$this->search = $search;
		$this->replace = $replace;
	}

	/**
	 * @return StringReplaceFilter
	 **/
	public function setSearch($search) {
		$this->search = $search;

		return $this;
	}

	public function getSearch() {
		return $this->search;
	}

	/**
	 * @return StringReplaceFilter
	 **/
	public function setReplace($replace) {
		$this->replace = $replace;

		return $this;
	}

	public function getReplace() {
		return $this->replace;
	}

	public function getCount() {
		return $this->count;
	}

	public function apply($value) {
		if ($this->search === $this->replace) {
			return $value;
		}

		return str_replace($this->search, $this->replace, $value, $this->count);
	}
}
