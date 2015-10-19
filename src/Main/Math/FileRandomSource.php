<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

use Hesper\Core\Base\Assert;

/**
 * Class FileRandomSource
 * @package Hesper\Main\Math
 */
final class FileRandomSource implements RandomSource {

	private $handle = null;

	public function __construct($filename) {
		Assert::isTrue(file_exists($filename) && is_readable($filename));

		$this->handle = fopen($filename, 'rb');
	}

	public function __destruct() {
		fclose($this->handle);
	}

	public function getBytes($numberOfBytes) {
		Assert::isPositiveInteger($numberOfBytes);

		return fread($this->handle, $numberOfBytes);
	}
}
