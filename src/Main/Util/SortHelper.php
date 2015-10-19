<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

/**
 * Class SortHelper
 * @package Hesper\Main\Util
 */
final class SortHelper extends Singleton implements Instantiatable {

	const ASC  = 0x1;
	const DESC = 0x2;

	private $vector = null;
	private $keys   = null; // pairs of key name and direction

	private $defaultCmpFunction = 'strnatcmp';

	public static function me() {
		return Singleton::getInstance(self::class);
	}

	public function setVector(&$vector) {
		$this->vector = &$vector;

		return $this;
	}

	public function setKeys($keys) {
		$this->keys = $keys;

		foreach ($this->keys as &$keyData) {
			if (!isset($keyData[2])) {
				$keyData[2] = $this->defaultCmpFunction;
			}
		}

		return $this;
	}

	public function sort() {
		Assert::isGreater(count($this->keys), 0);
		Assert::isNotEmptyArray($this->vector);

		usort($this->vector, [$this, "compare"]);
	}

	private function compare($one, $two, $keyIndex = 0) {
		Assert::isTrue(isset($one[$this->keys[$keyIndex][0]]) || array_key_exists($this->keys[$keyIndex][0], $one), 'Key must be exist in vector!');

		$result = $this->keys[$keyIndex][2]($one[$this->keys[$keyIndex][0]], $two[$this->keys[$keyIndex][0]]);

		if ($this->keys[$keyIndex][1] == self::DESC) {
			$result *= -1;
		}

		if ($result == 0) {
			$keyIndex++;

			if (isset($this->keys[$keyIndex])) {
				$result = $this->compare($one, $two, $keyIndex);
			}
		}

		return $result;
	}
}
