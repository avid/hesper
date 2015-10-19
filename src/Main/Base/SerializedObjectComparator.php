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

final class SerializedObjectComparator extends Singleton implements Comparator, Instantiatable {

	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function compare($one, $two) {
		$serializedOne = serialize($one);
		$serializedTwo = serialize($two);

		if ($serializedOne == $serializedTwo) {
			return 0;
		}

		return ($serializedOne < $serializedTwo) ? -1 : 1;
	}
}
