<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

use Hesper\Main\Base\AbstractProtoClass;

/**
 * Interface Prototyped
 * @package Hesper\Core\Base
 */
interface Prototyped {

	/**
	 * @return AbstractProtoClass
	 **/
	public static function proto();
}
