<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Exception\BaseException;
use Hesper\Main\Util\FileUtils;

final class TempDirectory {

	private $path = null;

	public function __construct($directory = 'temp-garbage/', $prefix = 'TmpDir') {
		$this->path = FileUtils::makeTempDirectory($directory, $prefix);
	}

	public function __destruct() {
		try {
			FileUtils::removeDirectory($this->path, true);
		} catch (BaseException $e) {
			// boo! deal with garbage yourself.
		}
	}

	public function getPath() {
		return $this->path;
	}
}
