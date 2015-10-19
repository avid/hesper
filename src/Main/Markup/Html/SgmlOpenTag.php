<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class SgmlOpenTag
 * @package Hesper\Main\Markup\Html
 */
final class SgmlOpenTag extends SgmlTag {

	private $attributes = [];
	private $empty      = false;

	/**
	 * @return SgmlOpenTag
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return SgmlOpenTag
	 **/
	public function setEmpty($isEmpty) {
		Assert::isBoolean($isEmpty);

		$this->empty = $isEmpty;

		return $this;
	}

	public function isEmpty() {
		return $this->empty;
	}

	/**
	 * @return SgmlOpenTag
	 **/
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;

		return $this;
	}

	public function hasAttribute($name) {
		$name = strtolower($name);

		return isset($this->attributes[$name]);
	}

	public function getAttribute($name) {
		$name = strtolower($name);

		if (!isset($this->attributes[$name])) {
			throw new WrongArgumentException("attribute '{$name}' does not exist");
		}

		return $this->attributes[$name];
	}

	/**
	 * @return SgmlOpenTag
	 **/
	public function dropAttribute($name) {
		$name = strtolower($name);

		if (!isset($this->attributes[$name])) {
			throw new WrongArgumentException("attribute '{$name}' does not exist");
		}

		unset($this->attributes[$name]);

		return $this;
	}

	public function getAttributesList() {
		return $this->attributes;
	}

	/**
	 * @return SgmlOpenTag
	 **/
	public function dropAttributesList() {
		$this->attributes = [];

		return $this;
	}
}
