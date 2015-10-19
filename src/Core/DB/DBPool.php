<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\DAO\GenericDAO;

/**
 * Pool of DB's instances.
 * @package Hesper\Core\DB
 */
final class DBPool extends Singleton implements Instantiatable {

	private $default = null;

	private $pool = [];

	/**
	 * @return DBPool
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return DB
	 **/
	public static function getByDao(GenericDAO $dao) {
		return self::me()->getLink($dao->getLinkName());
	}

	/**
	 * @return DBPool
	 **/
	public function setDefault(DB $db) {
		$this->default = $db;

		return $this;
	}

	/**
	 * @return DBPool
	 **/
	public function dropDefault() {
		$this->default = null;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DBPool
	 **/
	public function addLink($name, DB $db) {
		if (isset($this->pool[$name])) {
			throw new WrongArgumentException("already have '{$name}' link");
		}

		$this->pool[$name] = $db;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return DBPool
	 **/
	public function dropLink($name) {
		if (!isset($this->pool[$name])) {
			throw new MissingElementException("link '{$name}' not found");
		}

		unset($this->pool[$name]);

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return DB
	 **/
	public function getLink($name = null) {
		$link = null;

		// single-DB project
		if (!$name) {
			if (!$this->default) {
				throw new MissingElementException('i have no default link and requested link name is null');
			}

			$link = $this->default;
		} elseif (isset($this->pool[$name])) {
			$link = $this->pool[$name];
		}

		if ($link) {
			if (!$link->isConnected()) {
				$link->connect();
			}

			return $link;
		}

		throw new MissingElementException("can't find link with '{$name}' name");
	}

	/**
	 * @return DBPool
	 **/
	public function shutdown() {
		$this->disconnect();

		$this->default = null;
		$this->pool = [];

		return $this;
	}

	/**
	 * @return DBPool
	 **/
	public function disconnect() {
		if ($this->default) {
			$this->default->disconnect();
		}

		foreach ($this->pool as $db) {
			$db->disconnect();
		}

		return $this;
	}
}
