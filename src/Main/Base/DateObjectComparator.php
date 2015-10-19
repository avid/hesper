<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Date;
use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

final class DateObjectComparator extends Singleton implements Comparator, Instantiatable {

	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function compare(/*Date*/
		$one,/*Date*/
		$two) {
		Assert::isInstance($one, Date::class);
		Assert::isInstance($two, Date::class);

		$stamp1 = $one->toStamp();
		$stamp2 = $two->toStamp();

		if ($stamp1 == $stamp2) {
			return 0;
		}

		return ($stamp1 < $stamp2) ? -1 : 1;
	}
}
