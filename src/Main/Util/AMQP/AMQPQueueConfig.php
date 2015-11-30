<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Class AMQPQueueConfig
 * @see http://www.rabbitmq.com/amqp-0-9-1-quickref.html#queue.declare
 * @package Hesper\Main\Util\AMQP
 */
final class AMQPQueueConfig extends AMQPBaseConfig
{
	protected $exclusive = false;

	/**
	 * @return AMQPQueueConfig
	**/
	public static function create()
	{
		return new self();
	}

	public function getExclusive()
	{
		return $this->exclusive;
	}

	/**
	 * @param boolean $exclusive
	 * @return AMQPQueueConfig
	**/
	public function setExclusive($exclusive)
	{
		$this->exclusive = $exclusive === false;

		return $this;
	}

}