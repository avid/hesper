<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Main\Base\LightMetaProperty;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassProperty;
use Hesper\Meta\Entity\MetaRelation;

/**
 * Class SchemaBuilder
 * @package Hesper\Meta\Builder
 */
final class SchemaBuilder extends BaseBuilder {

	public static function buildTable($tableName, array $propertyList) {
		$out = <<<EOT
\$schema->
	addTable(
		\\Hesper\\Core\\OSQL\\DBTable::create('{$tableName}')->

EOT;

		$columns = [];

		/** @var MetaClassProperty $property */
		foreach ($propertyList as $property) {
			if ($property->getRelation() && ($property->getRelationId() != MetaRelation::ONE_TO_ONE)) {
				continue;
			}

			$column = $property->toColumn();

			if (is_array($column)) {
				$columns = array_merge($columns, $column);
			} else {
				$columns[] = $property->toColumn();
			}
		}

		$out .= implode("->\n", $columns);

		return $out . "\n);\n\n";
	}

	public static function buildRelations(MetaClass $class) {
		$out = null;

		$knownJunctions = [];

		foreach ($class->getAllProperties() as $property) {
			if ($relation = $property->getRelation()) {

				$foreignClass = $property->getType()
				                         ->getClass();

				if ($relation->getId() == MetaRelation::ONE_TO_MANY
					// nothing to build, it's in the same table
					// or table does not exist at all
					|| !$foreignClass->getPattern()
					                 ->tableExists() // no need to process them
					|| $class->getParent()
				) {
					continue;
				} elseif ($relation->getId() == MetaRelation::MANY_TO_MANY) {
					$tableName = $class->getTableName() . '_' . $foreignClass->getTableName();

					if (isset($knownJunctions[$tableName])) {
						continue;
					} // collision prevention
					else {
						$knownJunctions[$tableName] = true;
					}

					$foreignPropery = clone $foreignClass->getIdentifier();

					$name = $class->getName();
					$name = strtolower($name[0]) . substr($name, 1);
					$name .= 'Id';

					$foreignPropery->setName($name)
					               ->setColumnName($foreignPropery->getConvertedName())
					               ->// we don't need primary key here
					               setIdentifier(false);

					// we don't want any garbage in such tables
					$property = clone $property;
					$property->required();

					// prevent name collisions
					if ($property->getRelationColumnName() == $foreignPropery->getColumnName()) {
						$foreignPropery->setColumnName($class->getTableName() . '_' . $property->getConvertedName() . '_id');
					}

					$out .= <<<EOT
\$schema->
	addTable(
		\\Hesper\\Core\\OSQL\\DBTable::create('{$tableName}')->
		{$property->toColumn()}->
		{$foreignPropery->toColumn()}->
		addUniques('{$property->getRelationColumnName()}', '{$foreignPropery->getColumnName()}')
	);


EOT;
				} else {
					$sourceTable = $class->getTableName();
					$sourceColumn = $property->getRelationColumnName();

					$targetTable = $foreignClass->getTableName();
					$targetColumn = $foreignClass->getIdentifier()
					                             ->getColumnName();

					$out .= <<<EOT
// {$sourceTable}.{$sourceColumn} -> {$targetTable}.{$targetColumn}
\$schema->
	getTableByName('{$sourceTable}')->
		getColumnByName('{$sourceColumn}')->
			setReference(
				\$schema->
					getTableByName('{$targetTable}')->
					getColumnByName('{$targetColumn}'),
				\Hesper\Core\OSQL\ForeignChangeAction::restrict(),
				\Hesper\Core\OSQL\ForeignChangeAction::cascade()
			);


EOT;

				}
			}
		}

		return $out;
	}

	public static function getHead() {
		$out = parent::getHead();

		$out .= "\$schema = new \\Hesper\\Core\\OSQL\\DBSchema();\n\n";

		return $out;
	}
}
