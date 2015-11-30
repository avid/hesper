<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\Archiver;

use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Exception\WrongStateException;

/**
 * PECL ZipArchive proxy with Info-Zip wrapper.
 * @see http://pecl.php.net/package/zip
 * @package Hesper\Main\Util\Archiver
 */
final class InfoZipArchive extends FileArchive
{
	private $zipArchive = null;

	public function __construct($cmdBinPath = '/usr/bin/unzip')
	{
		$usingCmd = $cmdBinPath;

		if (class_exists('\ZipArchive', false)) {

			$this->zipArchive = new \ZipArchive();
			$usingCmd = null;

		} elseif ($usingCmd === null)
			throw
				new UnsupportedMethodException(
					'no built-in support for zip'
				);

		parent::__construct($usingCmd);
	}

	public function open($sourceFile)
	{
		parent::open($sourceFile);

		if ($this->zipArchive) {
			$resultCode = $this->zipArchive->open($sourceFile);

			if ($resultCode !== true)
				throw new ArchiverException(
					'ZipArchive::open() returns error code == '.$resultCode
				);
		}

		return $this;
	}

	public function readFile($fileName)
	{
		if (!$this->sourceFile)
			throw
				new WrongStateException(
					'dude, open an archive first.'
				);

		if ($this->zipArchive) {
			$result = $this->zipArchive->getFromName($fileName);

			if ($result === false)
				throw new ArchiverException(
					'ZipArchive::getFromName() failed'
				);

			return $result;
		}

		$options = '-c -q'
			.' '.escapeshellarg($this->sourceFile)
			.' '.escapeshellarg($fileName);

		return $this->execStdoutOptions($options);
	}
}