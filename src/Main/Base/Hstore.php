<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Stringable;
use Hesper\Core\Exception\ObjectNotFoundException;

/**
 * Class Hstore
 * @package Hesper\Main\Base
 */
final class Hstore implements Stringable {

	protected $properties = [];

	/**
	 * Create Hstore by raw string.
	 * @return Hstore
	 **/
	public static function create($string) {
		$self = new self();

		return $self->toValue($string);
	}

	/**
	 * Create Hstore by array.
	 * @return Hstore
	 **/
	public static function make($array) {
		$self = new self();

		return $self->setList($array);
	}

	/**
	 * @return Hstore
	 **/
	public function setList($array) {
		$this->properties = $array;

		return $this;
	}

	public function getList() {
		return $this->properties;
	}

	public function get($key) {
		if (!$this->isExists($key)) {
			throw new ObjectNotFoundException("Property with name '{$key}' does not exists");
		}

		return $this->properties[$key];
	}

	/**
	 * @return Hstore
	 **/
	public function set($key, $value) {
		$this->properties[$key] = $value;

		return $this;
	}

	/**
	 * @return Hstore
	 **/
	public function drop($key) {
		unset($this->properties[$key]);

		return $this;
	}

	public function isExists($key) {
		return array_key_exists($key, $this->properties);
	}

	/**
	 * @return Hstore
	 **/
	public function toValue($raw) {
		if (!$raw) {
			return $this;
		}

		$this->properties = $this->parseString($raw);

		return $this;
	}

	public function toString() {
		if (empty($this->properties)) {
			return null;
		}

		$string = '';

		foreach ($this->properties as $k => $v) {
			if ($v !== null) {
				$string .= "\"{$this->quoteValue($k)}\"=>\"{$this->quoteValue($v)}\",";
			} else {
				$string .= "\"{$this->quoteValue($k)}\"=>NULL,";
			}
		}

		return $string;
	}

	protected function quoteValue($value) {
		return addslashes($value);
	}

	private function parseString($raw) {
		$raw = preg_replace('/([$])/u', "\\\\$1", $raw);
		$unescapedHStore = [];
		eval('$unescapedHStore = array(' . $raw . ');');

		return $unescapedHStore;
	}
}
