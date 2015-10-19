<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enumeration;

/**
 * Class FetchStrategy
 * @package Hesper\Main\Criteria
 */
final class FetchStrategy extends Enumeration {

	const JOIN    = 1;
	const CASCADE = 2;
	const LAZY    = 3;

	protected $names = [self::JOIN => 'join', self::CASCADE => 'cascade', self::LAZY => 'lazy'];

	/**
	 * @return FetchStrategy
	 **/
	public function setId($id) {
		Assert::isNull($this->id, 'i am immutable one!');

		return parent::setId($id);
	}

	/**
	 * @return FetchStrategy
	 **/
	public static function join() {
		return self::getInstance(self::JOIN);
	}

	/**
	 * @return FetchStrategy
	 **/
	public static function cascade() {
		return self::getInstance(self::CASCADE);
	}

	/**
	 * @return FetchStrategy
	 **/
	public static function lazy() {
		return self::getInstance(self::LAZY);
	}

	/**
	 * @return FetchStrategy
	 **/
	private static function getInstance($id) {
		static $instances = [];

		if (!isset($instances[$id])) {
			$instances[$id] = new self($id);
		}

		return $instances[$id];
	}
}
