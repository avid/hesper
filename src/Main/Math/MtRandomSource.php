<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Singleton;

/**
 * based on pseudorandom generator mt_rand
 * @package Hesper\Main\Math
 */
final class MtRandomSource extends Singleton implements RandomSource {

	/**
	 * @return MtRandomSource
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function getBytes($numberOfBytes) {
		Assert::isPositiveInteger($numberOfBytes);

		$bytes = null;
		for ($i = 0; $i < $numberOfBytes; $i += 4) {
			$bytes .= pack('L', mt_rand());
		}

		return substr($bytes, 0, $numberOfBytes);
	}
}
