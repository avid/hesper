<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

/**
 * Class SgmlToken
 * @package Hesper\Main\Markup\Html
 */
class SgmlToken {

	private $value = null;

	/**
	 * @return SgmlToken
	 **/
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	public function getValue() {
		return $this->value;
	}
}
