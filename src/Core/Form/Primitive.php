<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Form\Primitive\BasePrimitive;
use Hesper\Core\Form\Primitive\ExplodedPrimitive;
use Hesper\Core\Form\Primitive\PrimitiveAlias;
use Hesper\Core\Form\Primitive\PrimitiveAnyType;
use Hesper\Core\Form\Primitive\PrimitiveArray;
use Hesper\Core\Form\Primitive\PrimitiveBinary;
use Hesper\Core\Form\Primitive\PrimitiveBoolean;
use Hesper\Core\Form\Primitive\PrimitiveClass;
use Hesper\Core\Form\Primitive\PrimitiveDate;
use Hesper\Core\Form\Primitive\PrimitiveDateRange;
use Hesper\Core\Form\Primitive\PrimitiveEnum;
use Hesper\Core\Form\Primitive\PrimitiveEnumByValue;
use Hesper\Core\Form\Primitive\PrimitiveEnumeration;
use Hesper\Core\Form\Primitive\PrimitiveEnumerationByValue;
use Hesper\Core\Form\Primitive\PrimitiveEnumerationList;
use Hesper\Core\Form\Primitive\PrimitiveEnumList;
use Hesper\Core\Form\Primitive\PrimitiveFile;
use Hesper\Core\Form\Primitive\PrimitiveFloat;
use Hesper\Core\Form\Primitive\PrimitiveForm;
use Hesper\Core\Form\Primitive\PrimitiveFormsList;
use Hesper\Core\Form\Primitive\PrimitiveHstore;
use Hesper\Core\Form\Primitive\PrimitiveHttpUrl;
use Hesper\Core\Form\Primitive\PrimitiveIdentifier;
use Hesper\Core\Form\Primitive\PrimitiveIdentifierList;
use Hesper\Core\Form\Primitive\PrimitiveImage;
use Hesper\Core\Form\Primitive\PrimitiveInet;
use Hesper\Core\Form\Primitive\PrimitiveInteger;
use Hesper\Core\Form\Primitive\PrimitiveIntegerIdentifier;
use Hesper\Core\Form\Primitive\PrimitiveIpAddress;
use Hesper\Core\Form\Primitive\PrimitiveIpRange;
use Hesper\Core\Form\Primitive\PrimitiveJson;
use Hesper\Core\Form\Primitive\PrimitiveList;
use Hesper\Core\Form\Primitive\PrimitiveMultiList;
use Hesper\Core\Form\Primitive\PrimitiveNoValue;
use Hesper\Core\Form\Primitive\PrimitivePlainList;
use Hesper\Core\Form\Primitive\PrimitivePolymorphicIdentifier;
use Hesper\Core\Form\Primitive\PrimitiveRange;
use Hesper\Core\Form\Primitive\PrimitiveRegistry;
use Hesper\Core\Form\Primitive\PrimitiveRegistryByValue;
use Hesper\Core\Form\Primitive\PrimitiveRegistryList;
use Hesper\Core\Form\Primitive\PrimitiveScalarIdentifier;
use Hesper\Core\Form\Primitive\PrimitiveString;
use Hesper\Core\Form\Primitive\PrimitiveTernary;
use Hesper\Core\Form\Primitive\PrimitiveTime;
use Hesper\Core\Form\Primitive\PrimitiveTimestamp;
use Hesper\Core\Form\Primitive\PrimitiveTimestampRange;
use Hesper\Core\Form\Primitive\PrimitiveTimestampTZ;
use Hesper\Core\Form\Primitive\PrimitiveUuid;
use Hesper\Core\Form\Primitive\PrimitiveUuidIdentifier;
use Hesper\Core\Form\Primitive\PrimitiveUuidIdentifierList;
use Hesper\Main\DAO\DAOConnected;

/**
 * Factory for various Primitives.
 * @package Hesper\Core\Form\Primitive
 */
final class Primitive extends StaticFactory {

	/**
	 * @return BasePrimitive
	 **/
	public static function spawn($primitive, $name) {
		Assert::classExists($primitive);

		return new $primitive($name);
	}

	/**
	 * @return PrimitiveAlias
	 **/
	public static function alias($name, BasePrimitive $prm) {
		return new PrimitiveAlias($name, $prm);
	}

	/**
	 * @return PrimitiveAnyType
	 **/
	public static function anyType($name) {
		return new PrimitiveAnyType($name);
	}

	/**
	 * @return PrimitiveInteger
	 **/
	public static function integer($name) {
		return new PrimitiveInteger($name);
	}

	/**
	 * @return PrimitiveFloat
	 **/
	public static function float($name) {
		return new PrimitiveFloat($name);
	}

	/**
	 * @return PrimitiveIdentifier
	 * @obsoleted by integerIdentifier and scalarIdentifier
	 **/
	public static function identifier($name) {
		return new PrimitiveIdentifier($name);
	}

	/**
	 * @return PrimitiveIntegerIdentifier
	 **/
	public static function integerIdentifier($name) {
		return new PrimitiveIntegerIdentifier($name);
	}

	/**
	 * @return PrimitiveScalarIdentifier
	 **/
	public static function scalarIdentifier($name) {
		return new PrimitiveScalarIdentifier($name);
	}

	/**
	 * @return PrimitivePolymorphicIdentifier
	 **/
	public static function polymorphicIdentifier($name) {
		return new PrimitivePolymorphicIdentifier($name);
	}

	/**
	 * @return PrimitiveIdentifierList
	 **/
	public static function identifierlist($name) {
		return new PrimitiveIdentifierList($name);
	}

	/**
	 * @return PrimitiveClass
	 **/
	public static function clazz($name) {
		return new PrimitiveClass($name);
	}

	/**
	 * @return PrimitiveEnumeration
	 **/
	public static function enumeration($name) {
		return new PrimitiveEnumeration($name);
	}

	/**
	 * @return PrimitiveEnumerationByValue
	 **/
	public static function enumerationByValue($name) {
		return new PrimitiveEnumerationByValue($name);
	}

	/**
	 * @return PrimitiveEnumerationList
	 **/
	public static function enumerationList($name) {
		return new PrimitiveEnumerationList($name);
	}

	/**
	 * @return PrimitiveDate
	 **/
	public static function date($name) {
		return new PrimitiveDate($name);
	}

	/**
	 * @return PrimitiveTimestamp
	 **/
	public static function timestamp($name) {
		return new PrimitiveTimestamp($name);
	}

	/**
	 * @return PrimitiveTimestampTZ
	 **/
	public static function timestampTZ($name) {
		return new PrimitiveTimestampTZ($name);
	}

	/**
	 * @return PrimitiveTime
	 **/
	public static function time($name) {
		return new PrimitiveTime($name);
	}

	/**
	 * @return PrimitiveString
	 **/
	public static function string($name) {
		return new PrimitiveString($name);
	}

	/**
	 * @return PrimitiveBinary
	 **/
	public static function binary($name) {
		return new PrimitiveBinary($name);
	}

	/**
	 * @return PrimitiveRange
	 **/
	public static function range($name) {
		return new PrimitiveRange($name);
	}

	/**
	 * @return PrimitiveDateRange
	 **/
	public static function dateRange($name) {
		return new PrimitiveDateRange($name);
	}

	/**
	 * @return PrimitiveTimestampRange
	 **/
	public static function timestampRange($name) {
		return new PrimitiveTimestampRange($name);
	}

	/**
	 * @return PrimitiveList
	 **/
	public static function choice($name) {
		return new PrimitiveList($name);
	}

	/**
	 * @return PrimitiveArray
	 **/
	public static function set($name) {
		return new PrimitiveArray($name);
	}

	/**
	 * @return PrimitiveHstore
	 **/
	public static function hstore($name) {
		return new PrimitiveHstore($name);
	}


	/**
	 * @param $name
	 * @return PrimitiveArray
	 */
	public static function json($name) {
		return new PrimitiveJson($name);
	}

	/**
	 * @param $name
	 * @return PrimitiveArray
	 */
	public static function jsonb($name) {
		return new PrimitiveJson($name);
	}

	/**
	 * @return PrimitiveMultiList
	 **/
	public static function multiChoice($name) {
		return new PrimitiveMultiList($name);
	}

	/**
	 * @return PrimitivePlainList
	 **/
	public static function plainChoice($name) {
		return new PrimitivePlainList($name);
	}

	/**
	 * @return PrimitiveBoolean
	 **/
	public static function boolean($name) {
		return new PrimitiveBoolean($name);
	}

	/**
	 * @return PrimitiveTernary
	 **/
	public static function ternary($name) {
		return new PrimitiveTernary($name);
	}

	/**
	 * @return PrimitiveFile
	 **/
	public static function file($name) {
		return new PrimitiveFile($name);
	}

	/**
	 * @return PrimitiveImage
	 **/
	public static function image($name) {
		return new PrimitiveImage($name);
	}

	/**
	 * @return ExplodedPrimitive
	 **/
	public static function exploded($name) {
		return new ExplodedPrimitive($name);
	}

	/**
	 * @return PrimitiveInet
	 **/
	public static function inet($name) {
		return new PrimitiveInet($name);
	}

	/**
	 * @return PrimitiveForm
	 **/
	public static function form($name) {
		return new PrimitiveForm($name);
	}

	/**
	 * @return PrimitiveFormsList
	 **/
	public static function formsList($name) {
		return new PrimitiveFormsList($name);
	}

	/**
	 * @return PrimitiveNoValue
	 **/
	public static function noValue($name) {
		return new PrimitiveNoValue($name);
	}

	/**
	 * @return PrimitiveHttpUrl
	 **/
	public static function httpUrl($name) {
		return new PrimitiveHttpUrl($name);
	}

	/**
	 * @return BasePrimitive
	 **/
	public static function prototyped($class, $propertyName, $name = null) {
		Assert::isInstance($class, Prototyped::class);

		$proto = is_string($class) ? call_user_func([$class, 'proto']) : $class->proto();

		if (!$name) {
			$name = $propertyName;
		}

		return $proto->getPropertyByName($propertyName)->makePrimitive($name);
	}

	/**
	 * @return PrimitiveIdentifier
	 **/
	public static function prototypedIdentifier($class, $name = null) {
		Assert::isInstance($class, DAOConnected::class);

		$dao = is_string($class) ? call_user_func([$class, 'dao']) : $class->dao();

		return self::prototyped($class, $dao->getIdName(), $name);
	}

	/**
	 * @return PrimitiveIpAddress
	 **/
	public static function ipAddress($name) {
		return new PrimitiveIpAddress($name);
	}

	/**
	 * @return PrimitiveIpRange
	 */
	public static function ipRange($name) {
		return new PrimitiveIpRange($name);
	}

	/**
	 * @return PrimitiveEnum
	 **/
	public static function enum($name) {
		return new PrimitiveEnum($name);
	}

	/**
	 * @return PrimitiveEnumByValue
	 **/
	public static function enumByValue($name) {
		return new PrimitiveEnumByValue($name);
	}

	/**
	 * @return PrimitiveEnumList
	 **/
	public static function enumList($name) {
		return new PrimitiveEnumList($name);
	}

	/**
	 * @return PrimitiveUuid
	 **/
	public static function uuid($name) {
		return new PrimitiveUuid($name);
	}

	/**
	 * @return PrimitiveUuid
	 **/
	public static function uuidIdentifier($name) {
		return new PrimitiveUuidIdentifier($name);
	}

	/**
	 * @return PrimitiveUuid
	 **/
	public static function uuidIdentifierList($name) {
		return new PrimitiveUuidIdentifierList($name);
	}

	/**
	 * @return PrimitiveRegistry
	 **/
	public static function registry($name)
	{
		return new PrimitiveRegistry($name);
	}

	/**
	 * @return PrimitiveRegistryByValue
	 **/
	public static function registryByValue($name)
	{
		return new PrimitiveRegistryByValue($name);
	}

	/**
	 * @return PrimitiveRegistryList
	 **/
	public static function registryList($name)
	{
		return new PrimitiveRegistryList($name);
	}

}
