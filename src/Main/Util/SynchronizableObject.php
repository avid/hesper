<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util;

/**
 * Interface SynchronizableObject
 * @package Hesper\Main\Util
 */
interface SynchronizableObject {

	public static function createFromMasterObject($masterObject);

	public function isEqualTo($anotherObject);

	public function __toString();
}
