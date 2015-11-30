<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP\Pecl;

use Hesper\Main\Util\AMQP\AMQP;
use Hesper\Main\Util\AMQP\AMQPCredentials;
use Hesper\Main\Util\AMQP\AMQPInterface;
use Hesper\Main\Util\AMQP\Exception\AMQPServerConnectionException;

/**
 * Class AMQPPecl
 * @see http://www.php.net/manual/en/book.amqp.php
 * @package Hesper\Main\Util\AMQP\Pecl
 */
final class AMQPPecl extends AMQP
{
	public function __construct(AMQPCredentials $credentials)
	{
		parent::__construct($credentials);

		$this->fillCredentials();
	}

	/**
	 * @return boolean
	**/
	public function isConnected()
	{
		try {
			return $this->link->isConnected();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * @throws AMQPServerConnectionException
	 * @return AMQP
	**/
	public function connect()
	{
		try {
			if ($this->isConnected())
				return $this;

			$this->link->connect();

		} catch (\AMQPConnectionException $e) {
			$this->alive = false;

			throw new AMQPServerConnectionException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this;
	}

	/**
	 * @throws AMQPServerConnectionException
	 * @return AMQP
	**/
	public function reconnect()
	{
		try {
			$this->link->reconnect();
			return $this;
		} catch (\AMQPConnectionException $e) {
			$this->alive = false;

			throw new AMQPServerConnectionException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}
	}

	/**
	 * @throws AMQPServerConnectionException
	 * @return AMQP
	**/
	public function disconnect()
	{
		try {
			if ($this->isConnected()) {
				$this->link->disconnect();
				return $this;
			}
		} catch (\AMQPConnectionException $e) {
			$this->alive = false;

			throw new AMQPServerConnectionException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}
	}

	/**
	 * @param mixed $id
	 * @param AMQPInterface $transport
	 * @return AMQPPeclChannel
	**/
	public function spawnChannel($id, AMQPInterface $transport)
	{
		return new AMQPPeclChannel($id, $transport);
	}

	/**
	 * @return AMQPPecl
	**/
	protected function fillCredentials()
	{
		$this->link = new \AMQPConnection();
		$this->link->setHost($this->credentials->getHost());
		$this->link->setPort($this->credentials->getPort());
		$this->link->setLogin($this->credentials->getLogin());
		$this->link->setPassword($this->credentials->getPassword());
		$this->link->setVHost($this->credentials->getVirtualHost());

		return $this;
	}
}
