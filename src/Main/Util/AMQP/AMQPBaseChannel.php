<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

use Hesper\Main\Util\AMQP\Exception\AMQPServerConnectionException;

/**
 * Base class modelling an AMQ channel
 * @package Hesper\Main\Util\AMQP
 */
abstract class AMQPBaseChannel implements AMQPChannelInterface
{
	protected $id = null;

	/**
	 * @var AMQPInterface
	**/
	protected $transport = null;

	public function __construct($id, AMQPInterface $transport)
	{
		$this->id = $id;
		$this->transport = $transport;
	}

	public function __destruct()
	{
		if ($this->isOpen())
			$this->close();
	}

	public function getTransport()
	{
		return $this->transport;
	}

	public function getId()
	{
		return $this->id;
	}

	/**
	 * @throws AMQPServerConnectionException
	 * @return AMQPBaseChannel
	**/
	protected function checkConnection()
	{
		if (!$this->transport->getLink()->isConnected()) {
			throw new AMQPServerConnectionException(
				"No connection available"
			);
		}

		return $this;
	}
}