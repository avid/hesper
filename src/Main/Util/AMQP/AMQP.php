<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * AMQP stands for Advanced Message Queue Protocol, which is
 * an open standard middleware layer for message routing and queuing.
 * @package Hesper\Main\Util\AMQP
 */
abstract class AMQP implements AMQPInterface
{
	/**
	 * @var AMQPCredentials
	**/
	protected $credentials = null;
	protected $link	= null;
	protected $alive = true;

	/**
	 * @var array of AMQPChannelInterface instances
	**/
	protected $channels	= array();

	/**
	 * @return AMQP
	**/
	abstract public function connect();

	/**
	 * @return AMQP
	**/
	abstract public function disconnect();

	/**
	 * @return AMQP
	**/
	abstract public function reconnect();

	/**
	 * @return boolean
	**/
	abstract public function isConnected();

	/**
	 * @return AMQPChannelInterface
	 */
	abstract public function spawnChannel($id, AMQPInterface $transport);

	public function __construct(AMQPCredentials $credentials)
	{
		$this->credentials = $credentials;
	}

	public function __destruct()
	{
		if ($this->isConnected()) {
			$this->disconnect();
		}
	}

	/**
	 * @return AMQP
	**/
	public static function spawn($class, AMQPCredentials $credentials)
	{
		return new $class($credentials);
	}

	/**
	 * @return AMQP
	**/
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param integer $id
	 * @throws WrongArgumentException
	 * @return AMQPChannelInterface
	**/
	public function createChannel($id)
	{
		Assert::isInteger($id);

		if (isset($this->channels[$id]))
			throw new WrongArgumentException(
				"AMQP channel with id '{$id}' already registered"
			);

		if (!$this->isConnected())
			$this->connect();

		$this->channels[$id] =
			$this->spawnChannel($id, $this)->
			open();

		return $this->channels[$id];
	}

	/**
	 * @throws MissingElementException
	 * @return AMQPChannelInterface
	**/
	public function getChannel($id)
	{
		if (isset($this->channels[$id]))
			return $this->channels[$id];

		throw new MissingElementException(
			"Can't find AMQP channel with id '{$id}'"
		);
	}

	/**
	 * @return array
	**/
	public function getChannelList()
	{
		return $this->channels;
	}

	/**
	 * @param integer $id
	 * @throws MissingElementException
	 * @return AMQPChannelInterface
	**/
	public function dropChannel($id)
	{
		if (!isset($this->channels[$id]))
			throw new MissingElementException(
				"AMQP channel with id '{$id}' not found"
			);

		$this->channels[$id]->close();

		unset($this->channels[$id]);

		return $this;
	}

	/**
	 * @return AMQPCredentials
	 */
	public function getCredentials()
	{
		return $this->credentials;
	}

	/**
	 * @return bool
	 */
	public function isAlive()
	{
		return $this->alive;
	}

	/**
	 * @param bool $alive
	 * @return AMQP
	 */
	public function setAlive($alive)
	{
		$this->alive = ($alive === true);

		return $this;
	}

}