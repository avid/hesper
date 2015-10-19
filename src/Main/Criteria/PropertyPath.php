<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\AbstractProtoClass;
use Hesper\Main\Base\LightMetaProperty;
use Hesper\Main\DAO\DAOConnected;
use Hesper\Main\DAO\ProtoDAO;
use Hesper\Main\Util\ClassUtils;

/**
 * Class PropertyPath
 * @package Hesper\Main\Criteria
 */
final class PropertyPath {

	private $root = null;
	private $path = null;

	private $properties = [];

	private static $daos   = [];
	private static $protos = []; // zergs suck anyway ;-)

	public function __construct($root, $path) {
		Assert::isString($path, 'non-string path given');

		if (is_object($root)) {
			$className = get_class($root);
		} else {
			Assert::classExists($root);

			$className = $root;
		}

		$this->root = $className;
		$this->path = $path;

		$this->fetchHelpers($className);

		$proto = self::$protos[$className];

		$path = explode('.', $path);

		for ($i = 0, $size = count($path); $i < $size; ++$i) {
			$this->properties[$i] = $property = $proto->getPropertyByName($path[$i]);

			if ($className = $property->getClassName()) {
				$this->fetchHelpers($className);
				$proto = self::$protos[$className];
			} elseif ($i < $size) {
				continue;
			} else {
				throw new WrongArgumentException('corrupted path');
			}
		}
	}

	public function getPath() {
		return $this->path;
	}

	public function getRoot() {
		return $this->root;
	}

	/**
	 * @return AbstractProtoClass
	 **/
	public function getFinalProto() {
		return self::$protos[$this->getFinalProperty()->getClassName()];
	}

	/**
	 * @return ProtoDAO
	 **/
	public function getFinalDao() {
		return self::$daos[$this->getFinalProperty()->getClassName()];
	}

	/**
	 * @return LightMetaProperty
	 **/
	public function getFinalProperty() {
		return end($this->properties);
	}

	/* void */
	private function fetchHelpers($className) {
		if (isset(self::$protos[$className], self::$daos[$className])) {
			return /* boo */;
		}

		self::$protos[$className] = call_user_func([$className, 'proto']);
		self::$daos[$className] = ClassUtils::isInstanceOf($className, DAOConnected::class) ? call_user_func([$className, 'dao']) : null;

		Assert::isTrue((self::$protos[$className] instanceof AbstractProtoClass) && (self::$daos[$className] instanceof ProtoDAO || self::$daos[$className] === null));
	}
}
