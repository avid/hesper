<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Class AMQPDefaultConsumer
 * @package Hesper\Main\Util\AMQP
 */
abstract class AMQPDefaultConsumer implements AMQPConsumer
{
	/**
	 * @var AMQPChannelInterface
	**/
	protected $channel = null;
	protected $consumerTag = null;
	protected $autoAcknowledge = false;
	protected $queueName = null;

	public function __construct(AMQPChannelInterface $channel)
	{
		$this->channel = $channel;
	}

	/**
	 * @return AMQPChannelInterface
	**/
	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 * @param $consumerTag
	 * @return AMQPConsumer
	**/
	public function setConsumerTag($consumerTag)
	{
		$this->consumerTag = $consumerTag;

		return $this;
	}

	public function getConsumerTag()
	{
		return $this->consumerTag;
	}

	/**
	 * @return void
	**/
	public function handleConsumeOk($consumerTag)
	{
		// no work to do
	}

	/**
	 * @return void
	**/
	public function handleCancelOk($consumerTag)
	{
		// no work to do
	}

	/**
	 * @return void
	**/
	public function handleDelivery(AMQPIncomingMessage $delivery)
	{
		// no work to do
	}

	/**
	 * @return void
	**/
	public function handleChangeConsumerTag($fromTag, $toTag)
	{
		// no work to do
	}

	/**
	 * @return AMQPDefaultConsumer
	**/
	public function setQueueName($name)
	{
		$this->queueName = $name;

		return $this;
	}

	/**
	 * @return string
	 **/
	public function getQueueName()
	{
		return $this->queueName;
	}

	/**
	 * @return AMQPDefaultConsumer
	**/
	public function setAutoAcknowledge($boolean)
	{
		$this->autoAcknowledge = ($boolean === true);

		return $this;
	}

	public function isAutoAcknowledge()
	{
		return $this->autoAcknowledge;
	}

	/**
	 * @return AMQPIncomingMessage
	**/
	public function getNextDelivery()
	{
		return $this->channel->getNextDelivery();
	}
}