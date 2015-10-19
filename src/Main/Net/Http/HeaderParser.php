<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Net\Http;

/**
 * Class HeaderParser
 * @package Hesper\Main\Net\Http
 */
final class HeaderParser {

	private $headers       = [];
	private $currentHeader = null;

	public static function create() {
		return new self;
	}

	/**
	 * @param $data raw header data
	 * @return HeaderParser
	 */
	public function parse($data) {
		$lines = explode("\n", $data);

		foreach ($lines as $line) {
			$this->doLine($line);
		}

		return $this;
	}

	public function doLine($line) {
		$line = trim($line, "\r\n");
		$matches = [];

		if (preg_match("/^([\w-]+):\s+(.+)/", $line, $matches)) {

			$name = strtolower($matches[1]);
			$value = $matches[2];
			$this->currentHeader = $name;

			if (isset($this->headers[$name])) {
				if (!is_array($this->headers[$name])) {
					$this->headers[$name] = [$this->headers[$name]];
				}
				$this->headers[$name][] = $value;
			} else {
				$this->headers[$name] = $value;
			}

		} elseif (preg_match("/^\s+(.+)$/", $line, $matches) && $this->currentHeader !== null) {
			if (is_array($this->headers[$this->currentHeader])) {
				$lastKey = count($this->headers[$this->currentHeader]) - 1;
				$this->headers[$this->currentHeader][$lastKey] .= $matches[1];
			} else {
				$this->headers[$this->currentHeader] .= $matches[1];
			}
		}

		return $this;
	}

	/**
	 * @return array (associative) of headers (name => value)
	 **/
	public function getHeaders() {
		return $this->headers;
	}

	public function hasHeader($name) {
		return isset($this->headers[strtolower($name)]);
	}

	public function getHeader($name) {
		return $this->headers[strtolower($name)];
	}
}
