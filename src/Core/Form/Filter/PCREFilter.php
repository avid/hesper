<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sveta A. Smirnova
 */
namespace Hesper\Core\Form\Filter;

/**
 * Class PCREFilter
 * @package Hesper\Core\Form\Filter
 */
final class PCREFilter implements Filtrator {

	private $search  = null;
	private $replace = null;
	private $limit   = -1;

	/**
	 * @return PCREFilter
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return PCREFilter
	 **/
	public function setExpression($search, $replace) {
		$this->search = $search;
		$this->replace = $replace;

		return $this;
	}

	public function apply($value) {
		return preg_replace($this->search, $this->replace, $value, $this->limit);
	}

	/**
	 * @return PCREFilter
	 **/
	public function setLimit($limit) {
		$this->limit = $limit;

		return $this;
	}
}
