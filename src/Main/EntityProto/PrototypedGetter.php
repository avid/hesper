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
 * Class PrototypedGetter
 * @package Hesper\Main\EntityProto
 */
abstract class PrototypedGetter extends PrototypedMethodCaller {

	abstract public function get($name);

	public function getSetter() {
		throw new UnimplementedFeatureException('inverse operation is not defined yet');
	}
}
