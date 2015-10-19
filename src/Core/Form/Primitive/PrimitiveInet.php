<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class PrimitiveInet
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveInet extends BasePrimitive {

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if (is_string($scope[$this->name]) && (($length = strlen($scope[$this->name])) < 16) && (substr_count($scope[$this->name], '.', null, $length) == 3) && (ip2long($scope[$this->name]) !== false)) {
			$this->value = $scope[$this->name];

			return true;
		}

		return false;
	}
}
