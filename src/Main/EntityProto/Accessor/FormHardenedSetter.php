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
 * Class FormHardenedSetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormHardenedSetter extends FormMutator {

	public function set($name, $value) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$method = ($value === null) ? 'dropValue' : 'setValue';

		$this->object->get($primitive->getName())->$method($value);

		return $this;
	}
}
