<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Base common parent for all aggregate caches
 * @package Hesper\Core\Cache
 */
abstract class BaseAggregateCache extends SelectivePeer {

	protected $peers = [];

	/**
	 * @return BaseAggregateCache
	 **/
	public function dropPeer($label) {
		if (!isset($this->peers[$label])) {
			throw new MissingElementException("there is no peer with '{$label}' label");
		}

		unset($this->peer[$label]);

		return $this;
	}

	/**
	 * @return BaseAggregateCache
	 **/
	public function checkAlive() {
		$this->alive = false;

		foreach ($this->peers as $label => $peer) {
			if ($peer['object']->isAlive()) {
				$this->alive = true;
			} else {
				unset($this->peers[$label]);
			}
		}

		return $this->alive;
	}

	abstract protected function guessLabel($key);

	/**
	 * @return BaseAggregateCache
	 **/
	protected function doAddPeer($label, CachePeer $peer) {
		if (isset($this->peers[$label])) {
			throw new WrongArgumentException('use unique names for your peers');
		}

		if ($peer->isAlive()) {
			$this->alive = true;
		}

		$this->peers[$label]['object'] = $peer;
		$this->peers[$label]['stat'] = [];

		return $this;
	}

	/**
	 * low-level cache access
	 **/

	public function increment($key, $value) {
		$label = $this->guessLabel($key);

		if ($this->peers[$label]['object']->isAlive()) {
			return $this->peers[$label]['object']->increment($key, $value);
		} else {
			$this->checkAlive();
		}

		return null;
	}

	public function decrement($key, $value) {
		$label = $this->guessLabel($key);

		if ($this->peers[$label]['object']->isAlive()) {
			return $this->peers[$label]['object']->decrement($key, $value);
		} else {
			$this->checkAlive();
		}

		return null;
	}

	public function get($key) {
		$label = $this->guessLabel($key);

		if ($this->peers[$label]['object']->isAlive()) {
			return $this->peers[$label]['object']->get($key);
		} else {
			$this->checkAlive();
		}

		return null;
	}

	public function getList($indexes) {
		$labels = [];
		$out = [];

		foreach ($indexes as $index) {
			$labels[$this->guessLabel($index)][] = $index;
		}

		foreach ($labels as $label => $indexList) {
			if ($this->peers[$label]['object']->isAlive()) {
				if ($list = $this->peers[$label]['object']->getList($indexList)) {
					$out = array_merge($out, $list);
				}
			} else {
				$this->checkAlive();
			}
		}

		return $out;
	}

	public function delete($key) {
		$label = $this->guessLabel($key);

		if (!$this->peers[$label]['object']->isAlive()) {
			$this->checkAlive();

			return false;
		}

		return $this->peers[$label]['object']->delete($key);
	}

	/**
	 * @return AggregateCache
	 **/
	public function clean() {
		foreach ($this->peers as $peer) {
			$peer['object']->clean();
		}

		$this->checkAlive();

		return parent::clean();
	}

	public function getStats() {
		$stats = [];

		foreach ($this->peers as $level => $peer) {
			$stats[$level] = $peer['stat'];
		}

		return $stats;
	}

	public function append($key, $data) {
		$label = $this->guessLabel($key);

		if ($this->peers[$label]['object']->isAlive()) {
			return $this->peers[$label]['object']->append($key, $data);
		} else {
			$this->checkAlive();
		}

		return false;
	}

	protected function store($action, $key, $value, $expires = Cache::EXPIRES_MINIMUM) {
		$label = $this->guessLabel($key);

		if ($this->peers[$label]['object']->isAlive()) {
			return $this->peers[$label]['object']->$action($key, $value, $expires);
		} else {
			$this->checkAlive();
		}

		return false;
	}
}
