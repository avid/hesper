<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class InetType
 * @package Hesper\Meta\Type
 */
final class InetType extends IntegerType {

	public function getPrimitiveName() {
		return 'inet';
	}

	public function getSize() {
		return null;
	}

	/**
	 * @throws WrongArgumentException
	 * @return InetType
	 **/
	public function setDefault($default) {
		Assert::isTrue(long2ip(ip2long($default)) == $default, "strange default value given - '{$default}'");

		return parent::setDefault($default);
	}
}
