<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\InnerMetaProperty;
use Hesper\Main\Base\LightMetaProperty;
use Hesper\Main\Criteria\FetchStrategy;
use Hesper\Meta\Pattern\DictionaryClassPattern;
use Hesper\Meta\Pattern\EnumClassPattern;
use Hesper\Meta\Pattern\EnumerationClassPattern;
use Hesper\Meta\Pattern\InternalClassPattern;
use Hesper\Meta\Pattern\StraightMappingPattern;
use Hesper\Meta\Pattern\ValueObjectPattern;
use Hesper\Meta\Type\BasePropertyType;
use Hesper\Meta\Type\BooleanType;
use Hesper\Meta\Type\IntegerType;
use Hesper\Meta\Type\InternalType;
use Hesper\Meta\Type\NumericType;
use Hesper\Meta\Type\ObjectType;
use Hesper\Meta\Type\StringType;
use Hesper\Meta\Type\UuidType;

/**
 * Class MetaClassProperty
 * @package Hesper\Meta\Entity
 */
class MetaClassProperty {

	private $class = null;

	private $name       = null;
	private $columnName = null;

	private $type = null;
	private $size = null;

	private $required   = false;
	private $identifier = false;

	private $relation = null;

	private $strategy = null;

	public function __construct($name, BasePropertyType $type, MetaClass $class) {
		$this->name = $name;

		$this->type = $type;

		$this->class = $class;
	}

	public function equals(MetaClassProperty $property) {
		return (($property->getName() == $this->getName()) && ($property->getColumnName() == $this->getColumnName()) && ($property->getType() == $this->getType()) && ($property->getSize() == $this->getSize()) && ($property->getRelation() == $this->getRelation()) && ($property->isRequired() == $this->isRequired()) && ($property->isIdentifier() == $this->isIdentifier()));
	}

	/**
	 * @return MetaClass
	 **/
	public function getClass() {
		return $this->class;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	public function getColumnName() {
		return $this->columnName;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function setColumnName($name) {
		$this->columnName = $name;

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function getConvertedName() {
		return strtolower(preg_replace(':([A-Z]):', '_\1', $this->name));
	}

	/**
	 * @return BasePropertyType
	 **/
	public function getType() {
		return $this->type;
	}

	public function getSize() {
		return $this->size;
	}

	/**
	 * @throws WrongArgumentException
	 * @return MetaClassProperty
	 **/
	public function setSize($size) {
		if ($this->type instanceof NumericType) {
			if (strpos($size, ',') !== false) {
				list($size, $precision) = explode(',', $size, 2);

				$this->type->setPrecision($precision);
			}
		}

		Assert::isInteger($size, 'only integers allowed in size parameter');

		if ($this->type->isMeasurable()) {
			$this->size = $size;
		} else {
			throw new WrongArgumentException("size not allowed for '" . $this->getName() . '::' . get_class($this->type) . "' type");
		}

		return $this;
	}

	public function isRequired() {
		return $this->required;
	}

	public function isOptional() {
		return !$this->required;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function required() {
		$this->required = true;

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function optional() {
		$this->required = false;

		return $this;
	}

	public function isIdentifier() {
		return $this->identifier;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function setIdentifier($really = false) {
		$this->identifier = ($really === true);

		return $this;
	}

	/**
	 * @return MetaRelation
	 **/
	public function getRelation() {
		return $this->relation;
	}

	public function getRelationId() {
		if ($this->relation) {
			return $this->relation->getId();
		}

		return null;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function setRelation(MetaRelation $relation) {
		$this->relation = $relation;

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function setFetchStrategy(FetchStrategy $strategy) {
		$this->strategy = $strategy;

		return $this;
	}

	/**
	 * @return FetchStrategy
	 **/
	public function getFetchStrategy() {
		return $this->strategy;
	}

	public function getFetchStrategyId() {
		if ($this->strategy) {
			return $this->strategy->getId();
		} elseif ($this->getClass()->getFetchStrategyId() && ($this->getRelationId() == MetaRelation::ONE_TO_ONE) && ($this->getType() instanceof ObjectType) && (!$this->getType()->isGeneric())) {
			return $this->getClass()->getFetchStrategyId();
		}

		return null;
	}

	public function toMethods(MetaClass $class, MetaClassProperty $holder = null) {
		return $this->type->toMethods($class, $this, $holder);
	}

	public function getRelationColumnName() {
		if ($this->type instanceof ObjectType && !$this->type->isGeneric()) {
			if ($this->relation->getId() == MetaRelation::MANY_TO_MANY) {
				$columnName = $this->type->getClass()->getTableName() . '_id';
			} else {
				$columnName = $this->getColumnName();
			}
		} elseif ($this->type instanceof InternalType) {
			$out = [];
			foreach ($this->type->getSuffixList() as $suffix) {
				$out[] = $this->getColumnName() . '_' . $suffix;
			}

			return $out;
		} else {
			$columnName = $this->getColumnName();
		}

		return $columnName;
	}

	public function toColumn() {
		if ($this->getType() instanceof ObjectType && (($this->getType() instanceof InternalType) || (!$this->getType()->isGeneric() && ($this->getType()->getClass()->getPattern() instanceof ValueObjectPattern)))) {
			$columns = [];

			$prefix = $this->getType() instanceof InternalType ? $this->getColumnName() . '_' : null;

			$remote = $this->getType()->getClass();

			foreach ($remote->getAllProperties() as $property) {
				$columns[] = $property->buildColumn($prefix . $property->getRelationColumnName());
			}

			return $columns;
		}

		return $this->buildColumn($this->getRelationColumnName());
	}

	/**
	 * @param MetaClass $holder
	 *
	 * @return LightMetaProperty
	 */
	public function toLightProperty(MetaClass $holder) {
		$className = null;
		$businessClassName = null;

		if (($this->getRelationId() == MetaRelation::ONE_TO_MANY) || ($this->getRelationId() == MetaRelation::MANY_TO_MANY)) {
			// collections
			$primitiveName = 'identifierList';
			$businessClassName = MetaClassNameBuilder::getClassOfMetaProperty($this, true);
		} elseif ($this->isIdentifier()) {
			if ($this->getType() instanceof IntegerType) {
				$primitiveName = 'integerIdentifier';
				$className = $holder->getName();
			} elseif ($this->getType() instanceof StringType) {
				$primitiveName = 'scalarIdentifier';
				$className = $holder->getName();
			} elseif ($this->getType() instanceof UuidType) {
				$primitiveName = 'uuidIdentifier';
				$className = $holder->getName();
			} else {
				$primitiveName = $this->getType()->getPrimitiveName();
			}
			$businessClassName = MetaClassNameBuilder::getClassOfMetaClass($holder, true);
		} elseif (!$this->isIdentifier() && !$this->getType()->isGeneric() && ($this->getType() instanceof ObjectType)) {
			$pattern = $this->getType()->getClass()->getPattern();

			if ($pattern instanceof EnumerationClassPattern) {
				$primitiveName = 'enumeration';
				$businessClassName = MetaClassNameBuilder::getClassOfMetaProperty($this, true);
			} elseif ($pattern instanceof EnumClassPattern) {
				$primitiveName = 'enum';
				$businessClassName = MetaClassNameBuilder::getClassOfMetaProperty($this, true);
			} elseif (($pattern instanceof DictionaryClassPattern || $pattern instanceof StraightMappingPattern) && ($identifier = $this->getType()->getClass()->getIdentifier())) {
				$businessClassName = MetaClassNameBuilder::getClassOfMetaProperty($this, true);
				if ($identifier->getType() instanceof IntegerType) {
					$primitiveName = 'integerIdentifier';
				} elseif ($identifier->getType() instanceof StringType) {
					$primitiveName = 'scalarIdentifier';
				} elseif ($identifier->getType() instanceof UuidType) {
					$primitiveName = 'uuidIdentifier';
				} else {
					$primitiveName = $this->getType()->getPrimitiveName();
				}
			} else {
				$primitiveName = $this->getType()->getPrimitiveName();
			}
		} else {
			if($this->getType() instanceof ObjectType) {
				$businessClassName = MetaClassNameBuilder::getClassOfMetaProperty($this, true);
			}
			$primitiveName = $this->getType()->getPrimitiveName();
		}

		$inner = false;

		if ($this->getType() instanceof ObjectType) {
			$className = $this->getType()->getClassName();

			if (!$this->getType()->isGeneric()) {
				$class = $this->getType()->getClass();
				$pattern = $class->getPattern();

				if ($pattern instanceof InternalClassPattern) {
					$className = $holder->getName();
				}

				if ((($pattern instanceof InternalClassPattern) || ($pattern instanceof ValueObjectPattern)) && ($className <> $holder->getName())) {
					$inner = true;
				}
			}
		}

		$propertyClassName = ($inner ? InnerMetaProperty::class : LightMetaProperty::class);
		if( empty($businessClassName) ) {
			$businessClassName = $className;
		}

		if (($this->getType() instanceof IntegerType)) {
			$size = $this->getType()->getSize();
		} elseif (($this->getType() instanceof ObjectType) && ($this->getRelationId() == MetaRelation::ONE_TO_ONE) && ($identifier = $this->getType()->getClass()->getIdentifier()) && ($this->getType()->isMeasurable())) {
			$size = $identifier->getType()->getSize();
		} elseif ($this->getType()->isMeasurable()) {
			$size = $this->size;
		} else {
			$size = null;
		}

		return call_user_func_array([$propertyClassName, 'fill'], [new $propertyClassName, $this->getName(), $this->getName() <> $this->getRelationColumnName() ? $this->getRelationColumnName() : null, $primitiveName, $businessClassName, $size, $this->isRequired(), $this->getType()->isGeneric(), $inner, $this->getRelationId(), $this->getFetchStrategyId()]);
	}

	private function buildColumn($columnName) {
		if (is_array($columnName)) {
			$out = [];

			foreach ($columnName as $name) {
				$out[] = $this->buildColumn($name);
			}

			return $out;
		}

		$column = <<<EOT
addColumn(
	\\Hesper\\Core\\OSQL\\DBColumn::create(
		{$this->type->toColumnType($this->size)}
EOT;

		if ($this->required) {
			$column .= <<<EOT
->
setNull(false)
EOT;
		}

		if ($this->size) {
			$column .= <<<EOT
->
setSize({$this->size})
EOT;
		}

		if ($this->type instanceof NumericType) {
			$column .= <<<EOT
->
setPrecision({$this->type->getPrecision()})
EOT;
		}

		$column .= <<<EOT
,
'{$columnName}'
)
EOT;

		if ($this->identifier) {
			$column .= <<<EOT
->
setPrimaryKey(true)
EOT;

			if ($this->getType() instanceof IntegerType) {
				$column .= <<<EOT
->
setAutoincrement(true)
EOT;
			}
		}

		if ($this->type->hasDefault()) {
			$default = $this->type->getDefault();

			if ($this->type instanceof BooleanType) {
				if ($default) {
					$default = 'true';
				} else {
					$default = 'false';
				}
			} elseif ($this->type instanceof StringType) {
				$default = "'{$default}'";
			}

			$column .= <<<EOT
->
setDefault({$default})
EOT;
		}

		$column .= <<<EOT

)
EOT;

		return $column;
	}

	private function toVarName($name) {
		return strtolower($name[0]) . substr($name, 1);
	}
}
