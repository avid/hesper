<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Core\Base\Assert;
use Hesper\Main\Util\AMQP\AMQPQueueConfig;

/**
 * Class AMQPPeclQueueBitmask
 * @see http://www.php.net/manual/en/amqp.constants.php
 * @package Hesper\Main\Util\AMQP\Pecl
 */
final class AMQPPeclQueueBitmask extends AMQPPeclBaseBitmask
{
	public function getBitmask($config)
	{
		Assert::isInstance($config, AMQPQueueConfig::class);

		$bitmask = parent::getBitmask($config);

		if ($config->getExclusive())
			$bitmask = $bitmask | AMQP_EXCLUSIVE;

		return $bitmask;
	}
}