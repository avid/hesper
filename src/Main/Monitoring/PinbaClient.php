<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Main\Monitoring;

use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Simple wrapper to pinba php extention
 * @see http://pinba.org/
 * @package Hesper\Main\Message
 */
final class PinbaClient extends Singleton {

	private static $enabled        = null;
	private        $timers         = [];
	private        $queue          = [];
	private        $treeLogEnabled = false;


	/**
	 * @return PinbaClient
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public static function isEnabled() {
		if (self::$enabled === null) {
			self::$enabled = ini_get("pinba.enabled") === "1";
		}

		return self::$enabled;
	}

	public function setTreeLogEnabled($orly = true) {
		$this->treeLogEnabled = ($orly === true);

		return $this;
	}

	public function isTreeLogEnabled() {
		return $this->treeLogEnabled;
	}

	public function getTreeQueue() {
		return $this->queue;
	}

	public function timerStart($name, array $tags, array $data = []) {
		if (array_key_exists($name, $this->timers)) {
			throw new WrongArgumentException('a timer with the same name allready exists');
		}

		if ($this->isTreeLogEnabled()) {

			$id = uniqid();
			$tags['treeId'] = $id;

			if (!empty($this->queue)) {
				$tags['treeParentId'] = end($this->queue);
			} else {
				$tags['treeParentId'] = 'root';
			}

			$this->queue[] = $id;
		}

		$this->timers[$name] = count($data) ? pinba_timer_start($tags, $data) : pinba_timer_start($tags);

		return $this;
	}

	public function timerStop($name) {
		if ($this->isTreeLogEnabled()) {
			array_pop($this->queue);
		}

		if (!array_key_exists($name, $this->timers)) {
			throw new WrongArgumentException('have no any timer with name ' . $name);
		}

		pinba_timer_stop($this->timers[$name]);

		unset($this->timers[$name]);

		return $this;
	}

	public function isTimerExists($name) {
		return array_key_exists($name, $this->timers);
	}

	public function timerDelete($name) {
		if (!array_key_exists($name, $this->timers)) {
			throw new WrongArgumentException('have no any timer with name ' . $name);
		}

		pinba_timer_delete($this->timers[$name]);

		unset($this->timers[$name]);

		return $this;
	}

	public function timerGetInfo($name) {
		if (!array_key_exists($name, $this->timers)) {
			throw new WrongArgumentException('have no any timer with name ' . $name);
		}

		return pinba_timer_get_info($this->timers[$name]);
	}

	public function setScriptName($name) {
		pinba_script_name_set($name);

		return $this;
	}

	public function setHostName($name) {
		pinba_hostname_set($name);

		return $this;
	}

	/**
	 * NOTE: You don't need to flush data manually. Pinba do it for you.
	 */
	public function flush() {
		pinba_flush();
	}
}
