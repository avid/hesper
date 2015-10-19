<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\Form\Filter;

/**
 * PHP password_hash based filter: passwords.
 * @package Hesper\Core\Form\Filter
 */
final class PasswordHash implements Filtrator {

	private $algorithm = PASSWORD_BCRYPT;

	public function __construct($algorithm = PASSWORD_BCRYPT) {
		$this->algorithm = $algorithm;
	}

	/**
	 * @return HashFilter
	 **/
	public static function create($algorithm = PASSWORD_BCRYPT) {
		return new self($algorithm);
	}

	public function getAlgorithm() {
		return $this->algorithm;
	}

	public function apply($value) {
		return password_hash($value, $this->algorithm);
	}

}
