<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Core\Base\Assert;
use Hesper\Main\Util\AMQP\AMQPExchangeConfig;

/**
 * Class AMQPPeclExchangeBitmask
 * @see http://www.php.net/manual/en/amqp.constants.php
 * @package Hesper\Main\Util\AMQP\Pecl
 */
final class AMQPPeclExchangeBitmask extends AMQPPeclBaseBitmask
{
	public function getBitmask($config)
	{
		Assert::isInstance($config, AMQPExchangeConfig::class);

		$bitmask = parent::getBitmask($config);

		if ($config->getInternal())
			$bitmask = $bitmask | AMQP_INTERNAL;

		return $bitmask;
	}
}