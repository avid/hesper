<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sveta A. Smirnova
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * Class ExplodedPrimitive
 * @package Hesper\Core\Form\Primitive
 */
final class ExplodedPrimitive extends PrimitiveString {

	protected $separator     = ' ';
	protected $splitByRegexp = false;

	/**
	 * @return ExplodedPrimitive
	 **/
	public function setSeparator($separator) {
		$this->separator = $separator;

		return $this;
	}

	public function getSeparator() {
		return $this->separator;
	}

	public function setSplitByRegexp($splitByRegexp = false) {
		$this->splitByRegexp = ($splitByRegexp === true);

		return $this;
	}

	public function isSplitByRegexp() {
		return $this->splitByRegexp;
	}

	public function import($scope) {
		if (!$result = parent::import($scope)) {
			return $result;
		}

		if ($this->value = $this->isSplitByRegexp() ? preg_split($this->separator, $this->value, -1, PREG_SPLIT_NO_EMPTY) : explode($this->separator, $this->value)) {
			return true;
		} else {
			return false;
		}
	}

	public function exportValue() {
		throw new UnimplementedFeatureException();
	}
}
