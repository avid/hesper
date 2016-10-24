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

final class TempFile {

	private $path = null;

	public function __construct($directory = '/temp-garbage/', $prefix = 'TmpFile') {
		$this->path = FileUtils::makeTempFile($directory, $prefix);
	}

	public function __destruct() {
		try {
			unlink($this->path);
		} catch (BaseException $e) {
			// boo! deal with garbage yourself.
		}
	}

	public function getPath() {
		return $this->path;
	}
}
