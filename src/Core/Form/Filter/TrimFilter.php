<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Filter;

/**
 * Class TrimFilter
 * @package Hesper\Core\Form\Filter
 */
final class TrimFilter implements Filtrator {

	const LEFT  = 'l';
	const RIGHT = 'r';
	const BOTH  = null;

	private $charlist  = null;
	private $direction = self::BOTH;

	/**
	 * @return TrimFilter
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return TrimFilter
	 **/
	public function setLeft() {
		$this->direction = self::LEFT;

		return $this;
	}

	/**
	 * @return TrimFilter
	 **/
	public function setRight() {
		$this->direction = self::RIGHT;

		return $this;
	}

	/**
	 * @return TrimFilter
	 **/
	public function setBoth() {
		$this->direction = self::BOTH;

		return $this;
	}

	public function apply($value) {
		$function = $this->direction . 'trim';

		return ($this->charlist ? $function($value, $this->charlist) : $function($value));
	}

	/**
	 * @return TrimFilter
	 **/
	public function setCharlist($charlist) {
		$this->charlist = $charlist;

		return $this;
	}
}
