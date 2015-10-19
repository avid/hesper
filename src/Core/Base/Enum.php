<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Core\Base;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Parent of all enumeration classes.
 * @package Hesper\Core\Base
 * @see     MimeType for example
 */
abstract class Enum extends NamedObject implements \Serializable {

	protected static $names = [/* override me */];

	/**
	 * @param integer $id
	 * @return static
	 */
	public static function create($id) {
		return new static($id);
	}

	public function __construct($id) {
		$this->setInternalId($id);
	}

	/**
	 * @param $id
	 *
	 * @return static
	 * @throws MissingElementException
	 */
	protected function setInternalId($id) {
		if (isset(static::$names[$id])) {
			$this->id = $id;
			$this->name = static::$names[$id];
		} else {
			throw new MissingElementException(get_class($this) . ' knows nothing about such id == ' . $id);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function serialize() {
		return (string)$this->id;
	}

	/**
	 * @param $serialized
	 */
	public function unserialize($serialized) {
		$this->setInternalId($serialized);
	}

	/**
	 * Array of object
	 * @static
	 * @return static[]
	 */
	public static function getList() {
		$list = [];
		foreach (array_keys(static::$names) as $id) {
			$list[] = static::create($id);
		}

		return $list;
	}

	/**
	 * must return any existent ID
	 * 1 should be ok for most enumerations
	 * @return integer
	 **/
	public static function getAnyId() {
		return 1;
	}

	/**
	 * @return null|integer
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * Alias for getList()
	 * @static
	 * @deprecated
	 * @return array
	 */
	public static function getObjectList() {
		return static::getList();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->name;
	}

	/**
	 * Plain list
	 * @static
	 * @return array
	 */
	public static function getNameList() {
		return static::$names;
	}

	/**
	 * @return static
	 **/
	public function setId($id) {
		throw new UnsupportedMethodException('You can not change id here, because it is politics for Enum!');
	}
}
