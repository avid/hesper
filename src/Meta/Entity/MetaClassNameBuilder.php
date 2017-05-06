<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Meta\Helper\NamespaceUtils;
use Hesper\Meta\Pattern\InternalCommonPattern;
use Hesper\Meta\Type\ObjectType;

class MetaClassNameBuilder extends StaticFactory {

	/**
	 * @param MetaClass $class
	 * @param bool      $addBackslash
	 *
	 * @return string
	 * @throws WrongArgumentException
	 */
	public static function getClassOfMetaClass(MetaClass $class, bool $addBackslash = false) {
		if( $class->getPattern() instanceof InternalCommonPattern ) {
			throw new WrongArgumentException();
		} else {
			return ($addBackslash ? '\\' : '') . NamespaceUtils::getBusinessNS($class) . '\\' . $class->getName();
		}
	}

	/**
	 * @param MetaClassProperty $property
	 * @param bool              $addBackslash
	 *
	 * @return string
	 * @throws WrongArgumentException
	 */
	public static function getClassOfMetaProperty(MetaClassProperty $property, bool $addBackslash = false) {
		$type = $property->getType();
		if( !($type instanceof ObjectType) ) {
			throw new WrongArgumentException();
		}
		$className = $addBackslash ? '\\' : '';
		if( $type->isGeneric() ) {
			$className .= $property->getType()->getFullClass();
		} else {
			if( $type->getClassName(){0}=='\\' ) {
				$className = $type->getClassName();
			} else {
//				$className .= NamespaceUtils::getBusinessNS($property->getClass()) . '\\' . $type->getClassName();
				$className .= self::guessFullClass($property->getClass(), $type->getClassName());
			}
		}
		return $className;
	}

	/**
	 * @param MetaClassProperty $property
	 * @param bool              $addBackslash
	 *
	 * @return string
	 * @throws WrongArgumentException
	 */
	public static function getContainerClassOfMetaProperty(MetaClassProperty $property, bool $addBackslash = false) {
		if(
			!($property->getType() instanceof ObjectType) ||
			is_null($property->getRelationId())
		) {
			throw new WrongArgumentException();
		}
		$className =
			($addBackslash ? '\\' : '')
			.$property->getClass()->getDaoNamespace()
			.'\\'
			.$property->getClass()->getName() . ucfirst($property->getName()) . 'DAO'
		;
		return $className;
	}

	private static function guessFullClass(MetaClass $source, string $targetClassName) {
		$nsmap = MetaConfiguration::me()->getNamespaceList();
		$nearest = $nsmap[$source->getNamespace()];
		if( in_array($targetClassName, $nearest['classes']) ) {
			return $source->getNamespace() . ($nearest['build']?'\\'.'Business':'') . '\\' . $targetClassName;
		}
		foreach( $nsmap as $ns=>$info ) {
			if( in_array($targetClassName, $info['classes']) ) {
				return $source->getNamespace() . ($info['build']?'\\'.'Business':'') . '\\' . $targetClassName;
			}
		}
		throw new MissingElementException("class `{$targetClassName}` was not found in any namespace");
	}

}