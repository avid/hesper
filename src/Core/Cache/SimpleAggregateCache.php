<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Base\Assert;

/**
 * A wrapper like AggregateCache, but it has very simple (and fast) selective algorithm
 * @package Hesper\Core\Cache
 */
final class SimpleAggregateCache extends AggregateCache {

	private $peerAmount = null;
	private $labels     = null;

	/**
	 * @return SimpleAggregateCache
	 **/
	public static function create() {
		return new self;
	}

	public function addPeer($label, CachePeer $peer, $level = self::LEVEL_NORMAL) {
		parent::addPeer($label, $peer, $level);

		return $this->dropHelpers();
	}

	public function dropPeer($label) {
		parent::dropPeer($label);

		return $this->dropHelpers();
	}

	public function checkAlive() {
		parent::checkAlive();

		return $this->dropHelpers();
	}

	/**
	 * brainless ;)
	 **/
	protected function guessLabel($key) {
		if ($this->peerAmount === null) {
			$this->peerAmount = count($this->peers);
		}

		if ($this->labels === null) {
			$this->labels = array_keys($this->peers);
		}

		Assert::isGreaterOrEqual($this->peerAmount, 1);

		return $this->labels[ord(substr($key, -1)) % $this->peerAmount];
	}

	private function dropHelpers() {
		$this->peerAmount = null;
		$this->labels = null;

		return $this;
	}
}
