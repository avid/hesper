<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Atom for ternary-based logic.
 * @ingroup Base
 * @ingroup Module
 **/
final class Ternary implements Stringable {

	private $trinity = null;    // ;-)

	public function __construct($boolean = null) {
		return $this->setValue($boolean);
	}

	/**
	 * @return Ternary
	 **/
	public static function create($boolean = null) {
		return new self($boolean);
	}

	/**
	 * @param mixed      $value
	 * @param mixed      $true
	 * @param mixed      $false
	 * @param mixed|null $null
	 *
	 * @return Ternary
	 * @throws WrongArgumentException
	 */
	public static function spawn($value, $true, $false, $null = null) {
		if ($value === $true) {
			return new Ternary(true);
		} elseif ($value === $false) {
			return new Ternary(false);
		} elseif (($value === $null) || ($null === null)) {
			return new Ternary(null);
		} else /* if ($value !== $null && $null !== null) or anything else */ {
			throw new WrongArgumentException("failed to spawn Ternary from '{$value}' switching on " . "'{$true}', '{$false}' and '{$null}'");
		}
	}

	public function isNull() {
		return (null === $this->trinity);
	}

	public function isTrue() {
		return (true === $this->trinity);
	}

	public function isFalse() {
		return (false === $this->trinity);
	}

	/**
	 * @return Ternary
	 **/
	public function setNull() {
		$this->trinity = null;

		return $this;
	}

	/**
	 * @return Ternary
	 **/
	public function setTrue() {
		$this->trinity = true;

		return $this;
	}

	/**
	 * @return Ternary
	 **/
	public function setFalse() {
		$this->trinity = false;

		return $this;
	}

	public function getValue() {
		return $this->trinity;
	}

	/**
	 * @return Ternary
	 **/
	public function setValue($boolean = null) {
		Assert::isTernaryBase($boolean);

		$this->trinity = $boolean;

		return $this;
	}

	public function decide($true, $false, $null = null) {
		if ($this->trinity === true) {
			return $true;
		} elseif ($this->trinity === false) {
			return $false;
		} elseif ($this->trinity === null) {
			return $null;
		}

		throw new WrongStateException('mama, weer all crazee now!' // (c) Slade
		);
	}

	public function toString() {
		return $this->decide('true', 'false', 'null');
	}
}
