<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Filter;

/**
 * SHA-1 based filter: passwords.
 * @package Hesper\Core\Form\Filter
 */
final class HashFilter implements Filtrator {

	private $binary = false;

	public function __construct($binary = false) {
		$this->binary = ($binary === true);
	}

	/**
	 * @return HashFilter
	 **/
	public static function create($binary = false) {
		return new self($binary);
	}

	public function isBinary() {
		return $this->binary;
	}

	public function apply($value) {
		return sha1($value, $this->binary);
	}
}
