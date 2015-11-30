<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Interface AMQPBitmaskResolver
 * @package Hesper\Main\Util\AMQP
 */
interface AMQPBitmaskResolver
{
	public function getBitmask($config);
}