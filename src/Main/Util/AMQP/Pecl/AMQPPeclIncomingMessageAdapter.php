<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeniya Tekalin
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Core\Base\StaticFactory;
use Hesper\Main\Util\AMQP\AMQPIncomingMessage;

/**
 * Class AMQPPeclIncomingMessageAdapter
 * @package Hesper\Main\Util\AMQP\Pecl
 */
final class AMQPPeclIncomingMessageAdapter extends StaticFactory
{
	/**
	 * @param \AMQPEnvelope $incoming
	 * @return AMQPIncomingMessage
	 */
	public static function convert(\AMQPEnvelope $incoming)
	{
		$data = array(
			AMQPIncomingMessage::APP_ID => $incoming->getAppId(),
			AMQPIncomingMessage::BODY => $incoming->getBody(),
			AMQPIncomingMessage::CONTENT_ENCODING => $incoming->getContentEncoding(),
			AMQPIncomingMessage::CONTENT_TYPE => $incoming->getContentType(),
			AMQPIncomingMessage::CORRELATION_ID => $incoming->getCorrelationId(),
			//AMQPIncomingMessage::COUNT => $incoming->getCount(),
			//AMQPIncomingMessage::CONSUME_BODY => $incoming->getConsumeBody(),
			//AMQPIncomingMessage::CONSUMER_TAG => $incoming->getConsumeTagName(),
			AMQPIncomingMessage::DELIVERY_TAG => $incoming->getDeliveryTag(),
			AMQPIncomingMessage::DELIVERY_MODE => $incoming->getDeliveryMode(),
			AMQPIncomingMessage::EXCHANGE => $incoming->getExchangeName(),
			AMQPIncomingMessage::EXPIRATION => $incoming->getExpiration(),
			AMQPIncomingMessage::MESSAGE_ID => $incoming->getMessageId(),
			AMQPIncomingMessage::PRIORITY => $incoming->getPriority(),
			AMQPIncomingMessage::REPLY_TO => $incoming->getReplyTo(),
			AMQPIncomingMessage::REDELIVERED => $incoming->isRedelivery(),
			AMQPIncomingMessage::PRIORITY => $incoming->getPriority(),
			AMQPIncomingMessage::ROUTING_KEY => $incoming->getRoutingKey(),
			AMQPIncomingMessage::TIMESTAMP => $incoming->getTimeStamp(),
			AMQPIncomingMessage::TYPE => $incoming->getType(),
			AMQPIncomingMessage::USER_ID => $incoming->getUserId()
		);

		return AMQPIncomingMessage::spawn($data);
	}

}
