<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Class AMQPOutgoingMessage
 * @package Hesper\Main\Util\AMQP
 */
final class AMQPOutgoingMessage extends AMQPBaseMessage
{
	protected $mandatory = false;
	protected $immediate = false;

	/**
	 * @return AMQPOutgoingMessage
	**/
	public static function create()
	{
		return new self;
	}

	public function getBitmask(AMQPBitmaskResolver $config)
	{
		return $config->getBitmask($this);
	}

	public function getMandatory()
	{
		return $this->mandatory;
	}

	/**
	 * @return AMQPOutgoingMessage
	**/
	public function setMandatory($mandatory)
	{
		$this->mandatory = $mandatory;

		return $this;
	}

	public function getImmediate()
	{
		return $this->immediate;
	}

	/**
	 * @return AMQPOutgoingMessage
	**/
	public function setImmediate($immediate)
	{
		$this->immediate = $immediate;

		return $this;
	}
}