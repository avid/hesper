<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message\Specification;

use Hesper\Core\Base\Timestamp;

/**
 * Interface Message
 * @package Hesper\Main\Message\Specification
 */
interface Message {

	/**
	 * @return Timestamp
	 **/
	public function getTimestamp();
}
