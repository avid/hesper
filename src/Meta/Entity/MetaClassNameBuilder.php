<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Meta\Pattern\InternalClassPattern;
use Hesper\Meta\Type\ObjectType;

class MetaClassNameBuilder extends StaticFactory {

	public static function getClassOfMetaClass(MetaClass $class, $addBackslash = false) {
		if( $class->getPattern() instanceof InternalClassPattern ) {
			throw new WrongArgumentException();
		} else {
			return ($addBackslash ? '\\' : '') . $class->getNamespace() . '\Business\\' . $class->getName();
		}
	}

	public static function getClassOfMetaProperty(MetaClassProperty $property, $addBackslash = false) {
		$type = $property->getType();
		if( !($type instanceof ObjectType) ) {
			throw new WrongArgumentException();
		}
		$className = $addBackslash ? '\\' : '';
		if( $type->isGeneric() ) {
			$className .= $property->getType()->getFullClass();
		} else {
			$className .= $property->getClass()->getNamespace().'\Business\\'.$type->getClassName();
		}
		return $className;
	}

	public static function getContainerClassOfMetaProperty(MetaClassProperty $property, $addBackslash = false) {
		if(
			!($property->getType() instanceof ObjectType) ||
			is_null($property->getRelationId())
		) {
			throw new WrongArgumentException();
		}
		$className =
			($addBackslash ? '\\' : '')
			.$property->getClass()->getNamespace()
			.'\DAO\\'
			.$property->getClass()->getName() . ucfirst($property->getName()) . 'DAO'
		;
		return $className;
	}

}