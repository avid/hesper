<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Garmonbozia Research Group
 */
namespace Hesper\Core\Base;

/**
 * Class Identifier
 * @package Hesper\Core\Base
 * @see     Identifiable
 */
final class Identifier implements Identifiable {

	private $id    = null;
	private $final = false;

	/**
	 * @return Identifier
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return Identifier
	 **/
	public static function wrap($id) {
		return self::create()->setId($id);
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * @return Identifier
	 **/
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return Identifier
	 **/
	public function finalize() {
		$this->final = true;

		return $this;
	}

	public function isFinalized() {
		return $this->final;
	}
}
