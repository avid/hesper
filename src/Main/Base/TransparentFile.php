<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Exception\WrongArgumentException;

final class TransparentFile {

	private $path    = null;
	private $rawData = null;

	private $tempFile = null;

	/**
	 * @return TransparentFile
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return TransparentFile
	 **/
	public function setPath($path) {
		if (!is_readable($path)) {
			throw new WrongArgumentException("cannot open source file {$path}");
		}

		$this->path = $path;

		$this->tempFile = null;
		$this->rawData = null;

		return $this;
	}

	public function getPath() {
		if (!$this->path && $this->rawData) {
			$this->tempFile = new TempFile();

			$this->path = $this->tempFile->getPath();

			file_put_contents($this->path, $this->rawData);
		}

		return $this->path;
	}

	/**
	 * @return TransparentFile
	 **/
	public function setRawData($rawData) {
		$this->rawData = $rawData;

		$this->tempFile = null;
		$this->path = null;

		return $this;
	}

	public function getRawData() {
		if (!$this->rawData && $this->path) {
			$this->rawData = file_get_contents($this->path);
		}

		return $this->rawData;
	}

	public function getSize() {
		if ($this->rawData) {
			return strlen($this->rawData);
		} elseif ($this->path) {
			return filesize($this->path);
		}

		return null;
	}
}
