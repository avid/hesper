<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Core\Base\Assert;
use Hesper\Main\Util\AMQP\AMQPBitmaskResolver;
use Hesper\Main\Util\AMQP\AMQPOutgoingMessage;

/**
 * Class AMQPPeclOutgoingMessageBitmask
 * @see http://www.php.net/manual/en/amqp.constants.php
 * @package Hesper\Main\Util\AMQP\Pecl
 */
final class AMQPPeclOutgoingMessageBitmask implements AMQPBitmaskResolver
{
	public function getBitmask($config)
	{
		Assert::isInstance($config, AMQPOutgoingMessage::class);

		$bitmask = 0;

		if ($config->getMandatory())
			$bitmask = $bitmask | AMQP_MANDATORY;

		if ($config->getImmediate())
			$bitmask = $bitmask | AMQP_IMMEDIATE;

		return $bitmask;
	}
}