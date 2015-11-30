<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

use Hesper\Core\Base\Enum;

/**
 * Class AMQPExchangeType
 * @package Hesper\Main\Util\AMQP
 */
final class AMQPExchangeType extends Enum
{
	const DIRECT = 1;
	const FANOUT = 2;
	const TOPIC = 3;
	const HEADER = 4;

	protected static $names = array(
		self::DIRECT => "direct",
		self::FANOUT => "fanout",
		self::TOPIC => "topic",
		self::HEADER => "header"
	);

	public function getDefault()
	{
		return self::DIRECT;
	}

	public function isDirect()
	{
		return $this->id == self::DIRECT;
	}

	public function isFanout()
	{
		return $this->id == self::FANOUT;
	}

	public function isTopic()
	{
		return $this->id == self::TOPIC;
	}

	public function isHeader()
	{
		return $this->id == self::HEADER;
	}
}