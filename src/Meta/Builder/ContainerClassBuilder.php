<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassNameBuilder;
use Hesper\Meta\Entity\MetaClassProperty;
use Hesper\Meta\Entity\MetaRelation;

/**
 * Class ContainerClassBuilder
 * @package Hesper\Meta\Builder
 */
final class ContainerClassBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		throw new UnsupportedMethodException();
	}

	public static function buildContainer(MetaClass $class, MetaClassProperty $holder) {
		$out = self::getHead();

		$out .= <<<EOT
namespace {$class->getNamespace()}\DAO;


EOT;

		$containerName = $class->getName() . ucfirst($holder->getName()) . 'DAO';
		$containerType = $holder->getRelation()->toString() . 'Linked';

		$uses = [
			'Hesper\Main\UnifiedContainer\\'.$containerType,
			MetaClassNameBuilder::getClassOfMetaClass($class),
			MetaClassNameBuilder::getClassOfMetaProperty($holder),
		];

		foreach ($uses as $import) {
			$out .= <<<EOT
use $import;

EOT;
		}
		$out .= <<<EOT


EOT;


		$out .= 'final class ' . $containerName . ' extends ' . $containerType . "\n{\n";

		$className = $class->getName();
		$propertyName = strtolower($className[0]) . substr($className, 1);

		$remoteColumnName = $holder->getType()->getClass()->getTableName();

		$out .= <<<EOT
public function __construct({$className} \${$propertyName}, \$lazy = false)
{
	parent::__construct(
		\${$propertyName},
		{$holder->getType()->getClassName()}::dao(),
		\$lazy
	);
}

/**
 * @return {$containerName}
**/
public static function create({$className} \${$propertyName}, \$lazy = false)
{
	return new self(\${$propertyName}, \$lazy);
}

EOT;

		if ($holder->getRelation()->getId() == MetaRelation::MANY_TO_MANY) {
			$out .= <<<EOT

public function getHelperTable()
{
	return '{$class->getTableName()}_{$remoteColumnName}';
}

public function getChildIdField()
{
	return '{$remoteColumnName}_id';
}

EOT;
		}

		$out .= <<<EOT

public function getParentIdField()
{
	return '{$class->getTableName()}_id';
}

EOT;

		$out .= "}\n";
		$out .= self::getHeel();

		return $out;
	}
}
