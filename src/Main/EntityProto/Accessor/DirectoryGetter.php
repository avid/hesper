<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Core\Form\Primitive\PrimitiveFile;
use Hesper\Core\Form\Primitive\PrimitiveForm;
use Hesper\Core\Form\Primitive\PrimitiveFormsList;
use Hesper\Main\EntityProto\PrototypedGetter;

/**
 * Class DirectoryGetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class DirectoryGetter extends PrototypedGetter {

	public function get($name) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$path = $this->object . '/' . $primitive->getName();

		if ($primitive instanceof PrimitiveFile) {
			return $path;
		}

		if (!file_exists($path)) {
			return null;
		}

		if ($primitive instanceof PrimitiveForm) {
			if (!$primitive instanceof PrimitiveFormsList) {
				return $path;
			}

			$result = [];

			$subDirs = glob($path . '/*');

			if ($subDirs === false) {
				throw new WrongStateException('cannot read directory ' . $path);
			}

			foreach ($subDirs as $path) {
				$result[basename($path)] = $path;
			}

			return $result;
		}

		for ($i = 0; $i <= 42; ++$i) { // yanetut
			$result = file_get_contents($path);

			if ($result === false) {
				throw new WrongArgumentException("failed to read $path");
			}

			if ($result) {
				break;
			}

			// NOTE: empty file COULD mean that data is being prepared now.
			// On heavy loaded systems it means that file was just
			// truncated and we should try again several times.
		}

		return $result;
	}
}
