<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Form\Filter\FilterChain;
use Hesper\Core\Form\Filter\Filtrator;

/**
 * Basis for Primitives which can be filtered.
 * @package Hesper\Core\Form\Primitive
 */
abstract class FiltrablePrimitive extends RangedPrimitive {

	private $importFilter  = null;
	private $displayFilter = null;

	public function __construct($name) {
		parent::__construct($name);

		$this->displayFilter = new FilterChain();
		$this->importFilter = new FilterChain();
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function setDisplayFilter(FilterChain $chain) {
		$this->displayFilter = $chain;

		return $this;
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function addDisplayFilter(Filtrator $filter) {
		$this->displayFilter->add($filter);

		return $this;
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function dropDisplayFilters() {
		$this->displayFilter = new FilterChain();

		return $this;
	}

	public function getDisplayValue() {
		if (is_array($value = $this->getActualValue())) {
			foreach ($value as &$element) {
				$element = $this->displayFilter->apply($element);
			}

			return $value;
		}

		return $this->displayFilter->apply($value);
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function setImportFilter(FilterChain $chain) {
		$this->importFilter = $chain;

		return $this;
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function addImportFilter(Filtrator $filter) {
		$this->importFilter->add($filter);

		return $this;
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	public function dropImportFilters() {
		$this->importFilter = new FilterChain();

		return $this;
	}

	/**
	 * @return FilterChain
	 **/
	public function getImportFilter() {
		return $this->importFilter;
	}

	/**
	 * @return FilterChain
	 **/
	public function getDisplayFilter() {
		return $this->displayFilter;
	}

	/**
	 * @return FiltrablePrimitive
	 **/
	protected function selfFilter() {
		if (is_array($this->value)) {
			foreach ($this->value as &$value) {
				$value = $this->importFilter->apply($value);
			}
		} else {
			$this->value = $this->importFilter->apply($this->value);
		}

		return $this;
	}
}
