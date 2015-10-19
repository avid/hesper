<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedSetter;

/**
 * Class ScopeSetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class ScopeSetter extends PrototypedSetter {

	private $getter = null;

	public function __construct(EntityProto $proto, &$object) {
		Assert::isArray($object);

		return parent::__construct($proto, $object);
	}

	public function set($name, $value) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		Assert::isTrue(!is_object($value), 'cannot put objects into scope');

		$primitive = $this->mapping[$name];

		$this->object[$primitive->getName()] = $value;

		return $this;
	}

	/**
	 * @return ScopeGetter
	 **/
	public function getGetter() {
		if (!$this->getter) {
			$this->getter = new ScopeGetter($this->proto, $this->object);
		}

		return $this->getter;
	}
}
