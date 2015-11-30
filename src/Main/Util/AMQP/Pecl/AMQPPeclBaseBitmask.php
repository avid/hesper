<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Main\Util\AMQP\AMQPBitmaskResolver;

/**
 * Class AMQPPeclBaseBitmask
 * @package Hesper\Main\Util\AMQP\Pecl
 */
abstract class AMQPPeclBaseBitmask implements AMQPBitmaskResolver
{
	public function getBitmask($config)
	{
		$bitmask = 0;

		if ($config->getPassive())
			$bitmask = $bitmask | AMQP_PASSIVE;

		if ($config->getDurable())
			$bitmask = $bitmask | AMQP_DURABLE;

		if ($config->getAutodelete())
			$bitmask = $bitmask | AMQP_AUTODELETE;

		if ($config->getNowait())
			throw new UnimplementedFeatureException();

		return $bitmask;
	}
}