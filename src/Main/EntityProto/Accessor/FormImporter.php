<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Primitive\PrimitiveForm;

/**
 * Class FormImporter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormImporter extends FormMutator {

	public function set($name, $value) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		if ($primitive instanceof PrimitiveForm) // inner form(s) has been already imported
		{
			$this->object->importValue($primitive->getName(), $value);
		} else {
			$this->object->importOne($primitive->getName(), [$primitive->getName() => $value]);
		}

		return $this;
	}
}
