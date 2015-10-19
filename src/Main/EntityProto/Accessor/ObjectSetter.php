<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\EntityProto\PrototypedSetter;

/**
 * Class ObjectSetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class ObjectSetter extends PrototypedSetter {

	private $getter = null;

	public function set($name, $value) {
		$setter = 'set' . ucfirst($name);
		$dropper = 'drop' . ucfirst($name);

		if ($value === null && method_exists($this->object, $dropper)) {
			$method = $dropper;
		} elseif (method_exists($this->object, $setter)) {
			$method = $setter;
		} else {
			throw new WrongArgumentException("cannot find mutator for '$name' in class " . get_class($this->object));
		}

		return $this->object->$method($value);
	}

	/**
	 * @return ObjectGetter
	 **/
	public function getGetter() {
		if (!$this->getter) {
			$this->getter = new ObjectGetter($this->proto, $this->object);
		}

		return $this->getter;
	}
}
