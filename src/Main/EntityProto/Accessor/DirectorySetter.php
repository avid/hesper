<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Primitive\PrimitiveFile;
use Hesper\Core\Form\Primitive\PrimitiveForm;

/**
 * Class DirectorySetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class DirectorySetter extends DirectoryMutator {

	public function set($name, $value) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		if ($value && !is_scalar($value) && !is_array($value)) {
			throw new UnimplementedFeatureException("directory services for property $name is unsupported yet");
		}

		$path = $this->object . '/' . $primitive->getName();

		if ($primitive instanceof PrimitiveFile) {
			if ($value && $value != $path && file_exists($value)) {
				copy($value, $path);
			}

			touch($path);

			return $this;

		} elseif ($primitive instanceof PrimitiveForm) {
			// under builder control
			return $this;
		}

		file_put_contents($path, $value);

		return $this;
	}
}
