<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\MissingElementException;

/**
 * Class ContentTypeHeader
 * @package Hesper\Main\Util
 */
final class ContentTypeHeader {

	private $mediaType  = null;
	private $parameters = [];

	private $charset = null; // reference

	/**
	 * @return ContentTypeHeader
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function setMediaType($mediaType) {
		$this->mediaType = $mediaType;

		return $this;
	}

	public function getMediaType() {
		return $this->mediaType;
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function setParameter($attribute, $value) {
		$this->parameters[$attribute] = $value;

		return $this;
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function dropParameter($attribute) {
		if (!isset($this->parameters[$attribute])) {
			throw new MissingElementException();
		}

		unset($this->parameters[$attribute]);

		return $this;
	}

	public function hasParameter($attribute) {
		return isset($this->parameters[$attribute]);
	}

	public function getParameter($attribute) {
		if (!isset($this->parameters[$attribute])) {
			throw new MissingElementException();
		}

		return $this->parameters[$attribute];
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function setParametersList($parameters) {
		Assert::isArray($parameters);

		$this->parameters = $parameters;

		return $this;
	}

	public function getParametersList() {
		return $this->parameters;
	}

	public function getCharset() {
		return $this->charset;
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function setCharset($charset) {
		if (!$this->charset) {
			$this->parameters['charset'] = $charset;
			$this->charset = &$this->parameters['charset'];
		} else {
			$this->charset = $charset;
		}

		return $this;
	}

	/**
	 * @return ContentTypeHeader
	 * sample argument: text/html; charset="utf-8"
	 **/
	public function import($string) {
		$this->charset = null;
		$this->parameters = [];
		$matches = [];

		if (preg_match('~^[\s\t]*([^/\s\t;]+/[^\s\t;]+)[\s\t]*(.*)$~', $string, $matches)) {
			$this->mediaType = $matches[1];
			$remainingString = $matches[2];

			preg_match_all('~;[\s\t]*([^\s\t\=]+)[\s\t]*\=[\s\t]*'    // 1: attribute
				. '(?:([^"\s\t;]+)|(?:"(.*?(?<!\\\))"))'    // 2 or 3: value
				. '[\s\t]*~', $remainingString, $matches);

			foreach ($matches[1] as $i => $attribute) {
				$attribute = strtolower($attribute);

				$value = empty($matches[2][$i]) ? $matches[3][$i] : $matches[2][$i];

				$this->parameters[$attribute] = $value;

				if ($attribute == 'charset') // NOTE: reference
				{
					$this->charset = &$this->parameters[$attribute];
				}
			}
		}

		return $this;
	}

	public function toString() {
		$parts = [$this->mediaType];

		foreach ($this->parameters as $attribute => $value) {
			$parts[] = $attribute . '="' . $value . '"';
		}

		return implode('; ', $parts);
	}
}
