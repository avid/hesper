<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class DirectoryContext
 * @package Hesper\Main\EntityProto
 */
final class DirectoryContext {

	private $map        = [];
	private $reverseMap = [];

	public function bind($name, $object) {
		if (!is_dir($name)) {
			throw new WrongArgumentException('directory ' . $name . ' does not exists');
		}

		if (isset($this->map[$name]) && $this->map[$name] !== $object) {
			throw new WrongArgumentException('consider using rebind()');
		}

		return $this->rebind($name, $object);
	}

	public function rebind($name, $object) {
		Assert::isNotNull($object);

		$this->map[$name] = $object;
		$this->reverseMap[spl_object_hash($object)] = $name;

		return $this;
	}

	public function lookup($name) {
		if (!isset($this->map[$name])) {
			return null;
		}

		return $this->map[$name];
	}

	public function reverseLookup($object) {
		if (!isset($this->reverseMap[spl_object_hash($object)])) {
			return null;
		}

		return $this->reverseMap[spl_object_hash($object)];
	}
}

