<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

use Hesper\Core\Base\Assert;

/**
 * Class Cdata
 * @package Hesper\Main\Markup\Html
 */
final class Cdata extends SgmlToken {

	private $data = null;

	private $strict = false;

	/**
	 * @return Cdata
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return Cdata
	 **/
	public function setData($data) {
		$this->data = $data;

		return $this;
	}

	public function getData() {
		if ($this->strict) {
			return '<![CDATA[' . $this->data . ']]>';
		} else {
			return $this->data;
		}
	}

	public function getRawData() {
		return $this->data;
	}

	/**
	 * @return Cdata
	 **/
	public function setStrict($isStrict) {
		Assert::isBoolean($isStrict);

		$this->strict = $isStrict;

		return $this;
	}

	public function isStrict() {
		return $this->strict;
	}
}
