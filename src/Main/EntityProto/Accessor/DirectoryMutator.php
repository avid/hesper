<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Base\Assert;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedSetter;

/**
 * Class DirectoryMutator
 * @package Hesper\Main\EntityProto\Accessor
 */
abstract class DirectoryMutator extends PrototypedSetter {

	private $getter = null;

	public function __construct(EntityProto $proto, &$object) {
		Assert::isTrue(is_dir($object) && is_writable($object), 'object must be a writeble directory');

		return parent::__construct($proto, $object);
	}

	/**
	 * @return FormGetter
	 **/
	public function getGetter() {
		if (!$this->getter) {
			$this->getter = new DirectoryGetter($this->proto, $this->object);
		}

		return $this->getter;
	}
}
