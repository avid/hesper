<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Class AMQPExchangeConfig
 * @see http://www.rabbitmq.com/amqp-0-9-1-quickref.html#exchange.declare
 * @package Hesper\Main\Util\AMQP
 */
final class AMQPExchangeConfig extends AMQPBaseConfig
{
	protected $internal = null;

	/**
	 * @var AMQPExchangeType
	**/
	protected $type = null;

	/**
	 * @return AMQPExchangeConfig
	**/
	public static function create()
	{
		return new self();
	}

	/**
	 * @param AMQPExchangeType $type
	 * @return AMQPExchangeConfig
	**/
	public function setType(AMQPExchangeType $type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return AMQPExchangeType
	**/
	public function getType()
	{
		return $this->type;
	}

	public function getInternal()
	{
		return $this->internal;
	}

	/**
	 * @param boolean $internal
	 * @return AMQPExchangeConfig
	**/
	public function setInternal($internal)
	{
		$this->internal = $internal;

		return $this;
	}
}