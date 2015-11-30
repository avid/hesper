<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeniya Tekalin
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Main\Util\AMQP\AMQPDefaultConsumer;
use Hesper\Main\Util\AMQP\AMQPIncomingMessage;

/**
 * Class AMQPPeclQueueConsumer
 * @package Hesper\Main\Util\AMQP\Pecl
 */
abstract class AMQPPeclQueueConsumer extends AMQPDefaultConsumer
{
	protected $cancel = false;
	protected $count = 0;
	protected $limit = 0;

	/**
	 * @param type $cancel
	 * @return AMQPPeclQueueConsumer
	 */
	public function setCancel($cancel)
	{
		$this->cancel = ($cancel === true);
		return $this;
	}

	/**
	 * @param int $limit
	 * @return AMQPPeclQueueConsumer
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	public function handlePeclDelivery(\AMQPEnvelope $delivery, \AMQPQueue $queue = null)
	{
		$this->count++;

		if ($this->limit && $this->count >= $this->limit)
			$this->setCancel(true);

		return $this->handleDelivery(
			AMQPPeclIncomingMessageAdapter::convert($delivery)
		);
	}

	public function handleDelivery(AMQPIncomingMessage $delivery)
	{
		if ($this->cancel) {
			$this->handleCancelOk('');
			return false;
		}
	}
}