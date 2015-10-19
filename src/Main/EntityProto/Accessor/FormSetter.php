<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class FormSetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormSetter extends FormMutator {

	public function set($name, $value) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$this->object->importValue($primitive->getName(), $value);

		return $this;
	}
}
