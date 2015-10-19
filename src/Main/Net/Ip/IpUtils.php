<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Net\Ip;

use Hesper\Core\Base\StaticFactory;

/**
 * Class IpUtils
 * @package Hesper\Main\Net\Ip
 */
final class IpUtils extends StaticFactory {

	public static function makeRanges(array $ips) {
		$ipsAsIntegers = [];

		foreach ($ips as $ip) {
			$ipsAsIntegers[] = ip2long($ip);
		}

		sort($ipsAsIntegers);

		$size = count($ipsAsIntegers);

		$ranges = [];

		$j = 0;

		$ranges[$j][] = long2ip($ipsAsIntegers[0]);

		for ($i = 1; $i < $size; ++$i) {
			if ($ipsAsIntegers[$i] != $ipsAsIntegers[$i - 1] + 1) {
				$ranges[++$j][] = long2ip($ipsAsIntegers[$i]); // start new range
			} else {
				$ranges[$j][] = long2ip($ipsAsIntegers[$i]);
			}
		}

		return $ranges;
	}
}
