<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\IdentifiableObject;
use Hesper\Main\Criteria\FetchStrategy;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassNameBuilder;
use Hesper\Meta\Entity\MetaClassProperty;
use Hesper\Meta\Entity\MetaRelation;
use Hesper\Meta\Pattern\DictionaryClassPattern;
use Hesper\Meta\Pattern\ValueObjectPattern;
use Hesper\Meta\Type\BooleanType;
use Hesper\Meta\Type\ObjectType;

/**
 * Class AutoClassBuilder
 * @package Hesper\Meta\Builder
 */
final class AutoClassBuilder extends BaseBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$out .= <<<EOT
namespace {$class->getAutoNamespace()};


EOT;

		$uses = [IdentifiableObject::class, MetaClassNameBuilder::getClassOfMetaClass($class)];
		foreach ($class->getProperties() as $property) {
			$dependency = null;
			if($property->getType() instanceof ObjectType) {
				if(
					$property->getRelationId()==MetaRelation::ONE_TO_MANY |
					$property->getRelationId()==MetaRelation::MANY_TO_MANY
				) {
					$dependency = MetaClassNameBuilder::getContainerClassOfMetaProperty($property);
				} else {
					$dependency = MetaClassNameBuilder::getClassOfMetaProperty($property);
				}
			} elseif($property->getType() instanceof BooleanType && $property->isOptional()) {
				$dependency = Assert::class;
			}
			if( !is_null($dependency) && !in_array($dependency, $uses) ) {
				$uses[] = $dependency;
			}
		}

		foreach($uses as $import) {
			$out .= <<<EOT
use $import;

EOT;
		}

		$out .= <<<EOT

abstract class Auto{$class->getName()}
EOT;

		$isNamed = false;

		if ($parent = $class->getParent()) {
			$out .= " extends {$parent->getBusinessClass(true)}";
		} elseif ($class->getPattern() instanceof DictionaryClassPattern && $class->hasProperty('name')) {
			$out .= " extends NamedObject";
			$isNamed = true;
		} elseif (!$class->getPattern() instanceof ValueObjectPattern) {
			$out .= " extends IdentifiableObject";
		}

		if ($interfaces = $class->getInterfaces()) {
			$out .= ' implements ' . implode(', ', $interfaces);
		}

		$out .= "\n{\n";

		foreach ($class->getProperties() as $property) {
			if (!self::doPropertyBuild($class, $property, $isNamed)) {
				continue;
			}

			if ($property->getFetchStrategyId() == FetchStrategy::LAZY) {
				$out .= "protected \${$property->getName()}Id = null;\n";
			} else {
				$out .= "protected \${$property->getName()} = " . "{$property->getType()->getDeclaration()};\n";
			}
		}

		$valueObjects = [];

		foreach ($class->getProperties() as $property) {
			if (
				$property->getType() instanceof ObjectType &&
				!$property->getType()->isGeneric() &&
				$property->getType()->getClass()->getPattern() instanceof ValueObjectPattern
			) {
				$valueObjects[$property->getName()] = $property->getType()->getClassName();
			}
		}

		if ($valueObjects) {
			$out .= <<<EOT

public function __construct()
{

EOT;
			foreach ($valueObjects as $propertyName => $className) {
				$out .= "\$this->{$propertyName} = new {$className}();\n";
			}

			$out .= "}\n";
		}

		foreach ($class->getProperties() as $property) {
			if (!self::doPropertyBuild($class, $property, $isNamed)) {
				continue;
			}

			$out .= $property->toMethods($class);
		}

		$out .= "}\n";
		$out .= self::getHeel();

		return $out;
	}

	private static function doPropertyBuild(MetaClass $class, MetaClassProperty $property, $isNamed) {
		if ($parentProperty = $class->isRedefinedProperty($property->getName())) {
			// check wheter property fetch strategy becomes lazy
			if (($parentProperty->getFetchStrategyId() <> $property->getFetchStrategyId()) && ($property->getFetchStrategyId() === FetchStrategy::LAZY)) {
				return true;
			}

			return false;
		}

		if ($isNamed && $property->getName() == 'name') {
			return false;
		}

		if (($property->getName() == 'id') && !$property->getClass()
		                                                ->getParent()
		) {
			return false;
		}

		// do not redefine parent's properties
		if ($property->getClass()
		             ->getParent() && array_key_exists($property->getName(), $property->getClass()
		                                                                              ->getAllParentsProperties())
		) {
			return false;
		}

		return true;
	}
}
