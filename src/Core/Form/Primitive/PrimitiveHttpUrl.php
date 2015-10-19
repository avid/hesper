<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class PrimitiveHttpUrl
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveHttpUrl extends PrimitiveString {

	private $checkPrivilegedPorts = false;

	public function setCheckPrivilegedPorts($check = true) {
		$this->checkPrivilegedPorts = $check ? true : false;

		return $this;
	}

	public function import($scope) {
		if (!$result = parent::import($scope)) {
			return $result;
		}

		try {
			$this->value = HttpUrl::create()
			                      ->parse($this->value)
			                      ->setCheckPrivilegedPorts($this->checkPrivilegedPorts);
		} catch (WrongArgumentException $e) {
			$this->value = null;

			return false;
		}

		if (!$this->value->isValid()) {
			$this->value = null;

			return false;
		}

		$this->value->normalize();

		return true;
	}

	public function importValue($value) {
		if ($value instanceof HttpUrl) {

			return $this->import([$this->getName() => $value->toString()]);
		} elseif (is_scalar($value)) {
			return parent::importValue($value);
		}

		return parent::importValue(null);
	}

	public function exportValue() {
		if (!$this->value) {
			return null;
		}

		return $this->value->toString();
	}
}
