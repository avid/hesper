<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Main\Base\AbstractProtoClass;
use Hesper\Meta\Entity\MetaClass;

/**
 * Class AutoProtoClassBuilder
 * @package Hesper\Meta\Builder
 */
final class AutoProtoClassBuilder extends BaseBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$parent = $class->getParent();

		if ($class->hasBuildableParent()) {
			$parentName = 'Proto' . $parent->getName();
			$uses = "{$parent->getProtoNamespace()}\\{$parentName}";
		} else {
			$parentName = 'AbstractProtoClass';
			$uses = AbstractProtoClass::class;
		}

		$out .= <<<EOT
namespace {$class->getAutoProtoNamespace()};

use $uses;


EOT;
		$out .= <<<EOT
abstract class AutoProto{$class->getName()} extends {$parentName}
{
EOT;
		$classDump = self::dumpMetaClass($class);

		$out .= <<<EOT

{$classDump}
}

EOT;

		return $out . self::getHeel();
	}

	private static function dumpMetaClass(MetaClass $class) {
		$propertyList = $class->getWithInternalProperties();

		$out = <<<EOT
	protected function makePropertyList()
	{

EOT;

		if ($class->hasBuildableParent()) {
			$out .= <<<EOT
		return
			array_merge(
				parent::makePropertyList(),
				array(

EOT;
			if ($class->getIdentifier()) {
				$propertyList[$class->getIdentifier()->getName()] = $class->getIdentifier();
			}
		} else {
			$out .= <<<EOT
		return array(

EOT;
		}

		$list = [];

		foreach ($propertyList as $property) {
			$list[] = "'{$property->getName()}' => " . $property->toLightProperty($class)->toString();
		}

		$out .= implode(",\n", $list);

		if ($class->hasBuildableParent()) {
			$out .= "\n)";
		}

		$out .= <<<EOT

		);
	}
EOT;

		return $out;
	}
}
