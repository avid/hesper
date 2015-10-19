<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

final class StandardComparator extends Singleton implements Comparator, Instantiatable {

	private $cmpFunction = 'strcmp';

	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function setCmpFunction($name) {
		$this->cmpFunction = $name;

		return $this;
	}

	public function compare($one, $two) {
		$cmpFunc = $this->cmpFunction;

		return $cmpFunc($one, $two);
	}
}
