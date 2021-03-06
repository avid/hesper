<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class AMQPPool
 * @todo BasePool implementations
 * @package Hesper\Main\Util\AMQP
 */
final class AMQPPool extends Singleton implements Instantiatable
{
	/** @var AMQP|null */
	private $default = null;
	/** @var AMQP[] */
	private $pool = array();

	/**
	 * @return AMQPPool
	**/
	public static function me()
	{
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return AMQPPool
	**/
	public function setDefault(AMQP $amqp)
	{
		$this->default = $amqp;

		return $this;
	}

	/**
	 * @return AMQPPool
	**/
	public function dropDefault()
	{
		$this->default = null;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return AMQPPool
	**/
	public function addLink($name, AMQP $amqp)
	{
		if (isset($this->pool[$name]))
			throw new WrongArgumentException(
				"amqp link with name '{$name}' already registered"
			);

		$this->pool[$name] = $amqp;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return AMQPPool
	**/
	public function dropLink($name)
	{
		if (!isset($this->pool[$name]))
			throw new MissingElementException(
				"amqp link with name '{$name}' not found"
			);

		unset($this->pool[$name]);

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return AMQP
	**/
	public function getLink($name = null)
	{
		$link = null;

		// single-amqp project
		if (!$name) {
			if (!$this->default)
				throw new MissingElementException(
					'i have no default amqp link and '
					.'requested link name is null'
				);

			$link = $this->default;
		} elseif (isset($this->pool[$name]))
			$link = $this->pool[$name];

		if ($link) {
			if (!$link->isConnected())
				$link->connect();

			return $link;
		}

		throw new MissingElementException(
			"can't find amqp link with '{$name}' name"
		);
	}

	/**
	 * @return AMQPPool
	**/
	public function shutdown()
	{
		$this->disconnect();

		$this->default = null;
		$this->pool = array();

		return $this;
	}

	/**
	 * @return AMQPPool
	**/
	public function disconnect()
	{
		if ($this->default)
			$this->default->disconnect();

		foreach ($this->pool as $amqp)
			$amqp->disconnect();

		return $this;
	}

	/**
	 * @return array
	 */
	public function getList()
	{
		$list = $this->pool;

		try {
			$list['default'] = $this->getLink();
		} catch (MissingElementException $e) {/**/}

		return $list;
	}
}