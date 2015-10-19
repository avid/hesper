<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Michael V. Tchervyakov
 */
namespace Hesper\Main\Markup;

/**
 * Class Csv
 * @see     http://tools.ietf.org/html/rfc4180
 * @todo    implement parse
 * @package Hesper\Main\Markup
 */
final class Csv {

	const SEPARATOR              = "\x2C";
	const QUOTE                  = "\x22";
	const CRLF                   = "\x0D\x0A";
	const QUOTE_REQUIRED_PATTERN = "/(\x2C|\x22|\x0D|\x0A)/";

	private $separator = self::SEPARATOR;

	private $header = false;
	private $data   = [];

	/**
	 * @return Csv
	 **/
	public static function create($header = false) {
		return new self($header);
	}

	public function __construct($header = false) {
		$this->header = (true === $header);
	}

	public function getArray() {
		return $this->data;
	}

	/**
	 * @return Csv
	 **/
	public function setArray($array) {
		Assert::isArray($array);

		$this->data = $array;

		return $this;
	}

	/**
	 * @return Csv
	 **/
	public function setSeparator($separator) {
		$this->separator = $separator;

		return $this;
	}

	public function parse($rawData) {
		throw new UnimplementedFeatureException('is not implemented yet');
	}

	public function render($forceQuotes = false) {
		Assert::isNotNull($this->separator);

		$csvString = null;

		foreach ($this->data as $row) {
			Assert::isArray($row);

			$rowString = null;

			foreach ($row as $value) {
				if ($forceQuotes || preg_match(self::QUOTE_REQUIRED_PATTERN, $value)) {
					$value = self::QUOTE . mb_ereg_replace(self::QUOTE, self::QUOTE . self::QUOTE, $value) . self::QUOTE;
				}

				$rowString .= ($rowString ? $this->separator : null) . $value;
			}

			$csvString .= $rowString . self::CRLF;
		}

		return $csvString;
	}

	/**
	 * @return ContentTypeHeader
	 **/
	public function getContentTypeHeader() {
		return ContentTypeHeader::create()->setParameter('header', $this->header ? 'present' : 'absent')->setMediaType('text/csv');
	}
}

?>