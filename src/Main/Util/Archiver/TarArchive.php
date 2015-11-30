<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\Archiver;

use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongStateException;

/**
 * GNU Tar wrapper.
 * @see http://www.gnu.org/software/tar/
 * @package Hesper\Main\Util\Archiver
 */
final class TarArchive extends FileArchive
{
	public function __construct($cmdBinPath = '/bin/tar')
	{
		if ($cmdBinPath === null)
			throw
				new UnimplementedFeatureException(
					'no built-in support for GNU Tar'
				);

		parent::__construct($cmdBinPath);
	}

	public function readFile($fileName)
	{
		if (!$this->sourceFile)
			throw
				new WrongStateException(
					'dude, open an archive first.'
				);

		$options = '--extract --to-stdout'
			.' --file '.escapeshellarg($this->sourceFile)
			.' '.escapeshellarg($fileName);

		return $this->execStdoutOptions($options);
	}
}