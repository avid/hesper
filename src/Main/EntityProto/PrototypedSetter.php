<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto;

use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * Class PrototypedSetter
 * @package Hesper\Main\EntityProto
 */
abstract class PrototypedSetter extends PrototypedMethodCaller {

	abstract public function set($name, $value);

	public function getGetter() {
		throw new UnimplementedFeatureException('inverse operation is not defined yet');
	}
}
