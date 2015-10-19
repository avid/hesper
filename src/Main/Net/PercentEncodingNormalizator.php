<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Net;

/**
 * Class PercentEncodingNormalizator
 * @package Hesper\Main\Net
 */
final class PercentEncodingNormalizator {

	private $unreservedPartChars = null;

	/**
	 * @return PercentEncodingNormalizator
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return PercentEncodingNormalizator
	 **/
	public function setUnreservedPartChars($unreservedPartChars) {
		$this->unreservedPartChars = $unreservedPartChars;

		return $this;
	}

	public function normalize($matched) {
		$char = $matched[0];
		if (mb_strlen($char) == 1) {
			if (!preg_match('/^[' . $this->unreservedPartChars . ']$/u', $char)) {
				$char = rawurlencode($char);
			}
		} else {
			if (preg_match('/^[' . GenericUri::CHARS_UNRESERVED . ']$/u', rawurldecode($char))) {
				$char = rawurldecode($char);
			} else {
				$char = strtoupper($char);
			}
		}

		return $char;
	}
}
