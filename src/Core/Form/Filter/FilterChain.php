<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Filter;

/**
 * Chained Filtrator.
 * @package Hesper\Core\Form\Filter
 */
final class FilterChain implements Filtrator {

	private $chain = [];

	/**
	 * @return FilterChain
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return FilterChain
	 **/
	public function add(Filtrator $filter) {
		$this->chain[] = $filter;

		return $this;
	}

	/**
	 * @return FilterChain
	 **/
	public function dropAll() {
		$this->chain = [];

		return $this;
	}

	public function apply($value) {
		foreach ($this->chain as $filter) {
			$value = $filter->apply($value);
		}

		return $value;
	}
}
