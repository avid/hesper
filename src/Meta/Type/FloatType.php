<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Nickolay G. Korolyov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * @ingroup Types
 **/
class FloatType extends IntegerType {

	protected $precision = 0;

	public function getPrimitiveName() {
		return 'float';
	}

	/**
	 * @throws WrongArgumentException
	 * @return FloatType
	 **/
	public function setDefault($default) {
		Assert::isFloat($default, "strange default value given - '{$default}'");

		$this->default = $default;

		return $this;
	}

	/**
	 * @return NumericType
	 **/
	public function setPrecision($precision) {
		$this->precision = $precision;

		return $this;
	}

	public function getPrecision() {
		return $this->precision;
	}

	public function isMeasurable() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::real()';
	}
}
