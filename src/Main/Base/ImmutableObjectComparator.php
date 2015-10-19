<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Base\Identifiable;

final class ImmutableObjectComparator extends Singleton implements Comparator, Instantiatable {

	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function compare($one, $two) {
		Assert::isInstance($one, Identifiable::class);
		Assert::isInstance($two, Identifiable::class);

		$oneId = $one->getId();
		$twoId = $two->getId();

		if ($oneId === $twoId) {
			return 0;
		}

		return ($oneId < $twoId) ? -1 : 1;
	}
}
