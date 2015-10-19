<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

use Hesper\Core\Exception\MissingElementException;

/**
 * Parent of all enumeration classes.
 * @package Hesper\Core\Base
 * @deprecated
 * @see     AccessMode for example
 */
abstract class Enumeration extends NamedObject implements \Serializable {

	protected $names = [/* override me */];

	final public function __construct($id) {
		$this->setId($id);
	}

	/// prevent's serialization of names' array
	//@{
	public function serialize() {
		return (string)$this->id;
	}

	public function unserialize($serialized) {
		$this->setId($serialized);
	}

	//@}

	public static function getList(Enumeration $enum) {
		return $enum->getObjectList();
	}

	/**
	 * must return any existent ID
	 * 1 should be ok for most enumerations
	 **/
	public static function getAnyId() {
		return 1;
	}

	/// parent's getId() is too complex in our case
	public function getId() {
		return $this->id;
	}

	public function getObjectList() {
		$list = [];
		$names = $this->getNameList();

		foreach (array_keys($names) as $id) {
			$list[] = new $this($id);
		}

		return $list;
	}

	public function toString() {
		return $this->name;
	}

	public function getNameList() {
		return $this->names;
	}

	/**
	 * @return Enumeration
	 **/
	public function setId($id) {
		$names = $this->getNameList();

		if (isset($names[$id])) {
			$this->id = $id;
			$this->name = $names[$id];
		} else {
			throw new MissingElementException(get_class($this) . ' knows nothing about such id == ' . $id);
		}

		return $this;
	}
}
