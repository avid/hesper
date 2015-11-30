<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

/**
 * Interface AMQPConsumer
 * @package Hesper\Main\Util\AMQP
 */
interface AMQPConsumer
{
	/**
	 * @return AMQPChannelInterface
	**/
	public function getChannel();

	/**
	 * Called when a delivery appears for this consumer.
	 * @param AMQPIncomingMessage $delivery
	 * @return void
	**/
	public function handleDelivery(AMQPIncomingMessage $delivery);

	/**
	 * Called when the consumer is first registered by a call
	 * to {@link Channel#basicConsume}.
	 *
	 * @param consumerTag the defined consumerTag
	 * @return void
	**/
	public function handleConsumeOk($consumerTag);

	/**
	 * Called when the consumer is deregistered by a call
	 * to {@link Channel#basicCancel}.
	 *
	 * @param consumerTag the defined consumerTag
	 * @return void
	**/
	public function handleCancelOk($consumerTag);

	/**
	 * Called when the consumer is changed tag
	 *
	 * @param string $fromTag
	 * @param string $toTag
	 * @return void
	**/
	public function handleChangeConsumerTag($fromTag, $toTag);

	/**
	 * @return AMQPConsumer
	**/
	public function setQueueName($name);

	/**
	 * @return string
	**/
	public function getQueueName();

	/**
	 * @return AMQPConsumer
	**/
	public function setAutoAcknowledge($boolean);

	/**
	 * @return boolean
	**/
	public function isAutoAcknowledge();

	/**
	 * @return AMQPConsumer
	**/
	public function setConsumerTag($consumerTag);

	/**
	 * @return string
	**/
	public function getConsumerTag();

	/**
	 * @return AMQPIncomingMessage
	**/
	public function getNextDelivery();
}