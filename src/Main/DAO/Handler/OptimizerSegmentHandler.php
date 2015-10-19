<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

/**
 * Class OptimizerSegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
abstract class OptimizerSegmentHandler implements SegmentHandler {

	protected $id     = null;
	protected $locker = null;

	abstract protected function getMap();

	abstract protected function storeMap(array $map);

	public function __construct($segmentId) {
		$this->id = $segmentId;
	}

	public function touch($key) {
		$map = $this->getMap();

		if (!isset($map[$key])) {
			$map[$key] = true;

			return $this->storeMap($map);
		}

		$this->locker->free($this->id);

		return true;
	}

	public function unlink($key) {
		$map = $this->getMap();

		if (isset($map[$key])) {
			unset($map[$key]);

			return $this->storeMap($map);
		}

		$this->locker->free($this->id);

		return true;
	}

	public function ping($key) {
		$map = $this->getMap();

		$this->locker->free($this->id);

		if (isset($map[$key])) {
			return true;
		} else {
			return false;
		}
	}
}
