<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enum;
use Hesper\Core\Base\Enumeration;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\Base\Registry;
use Hesper\Core\Base\Stringable;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive;
use Hesper\Core\Form\Primitive\BasePrimitive;
use Hesper\Core\Form\Primitive\IdentifiablePrimitive;
use Hesper\Core\Form\Primitive\PrimitiveInteger;
use Hesper\Core\OSQL\DBBinary;
use Hesper\Core\OSQL\InsertOrUpdateQuery;
use Hesper\Main\Criteria\FetchStrategy;
use Hesper\Main\DAO\ProtoDAO;
use Hesper\Main\Net\HttpUrl;
use Hesper\Meta\Entity\MetaRelation;
use Hesper\Core\OSQL\DBArray;

/**
 * Simplified MetaClassProperty for passing information between userspace and MetaConfiguration.
 * @package Hesper\Main\Base
 */
class LightMetaProperty implements Stringable {

    const UNSIGNED_FLAG = 0x1000;

    private static $limits = [0x0002 => [PrimitiveInteger::SIGNED_SMALL_MIN, PrimitiveInteger::SIGNED_SMALL_MAX], 0x1002 => [0, PrimitiveInteger::UNSIGNED_SMALL_MAX], 0x0004 => [PrimitiveInteger::SIGNED_MIN, PrimitiveInteger::SIGNED_MAX], 0x1004 => [0, PrimitiveInteger::UNSIGNED_MAX], 0x0008 => [PrimitiveInteger::SIGNED_BIG_MIN, PrimitiveInteger::SIGNED_BIG_MAX], 0x1008 => [0, null]];

    private $name = null;
    private $columnName = null;

    private $type = null;
    private $className = null;

    private $size = null;

    private $min = null;
    private $max = null;

    private $required = false;
    private $generic = false;
    private $inner = false;

    /// @see MetaRelation
    private $relationId = null;

    /// @see FetchStrategy
    private $strategyId = null;

    private $getter = null;
    private $setter = null;
    private $dropper = null;

    private $identifier = null;

    /**
     * @return LightMetaProperty
     **/
    public static function create() {
        return new self;
    }

    /**
     * must by in sync with InnerMetaProperty::make()
     * @return LightMetaProperty
     **/
    public static function fill(LightMetaProperty $property, $name, $columnName, $type, $className, $size, $required, $generic, $inner, $relationId, $strategyId) {
        $property->name = $name;

        $methodSuffix = ucfirst($name);
        $property->getter = 'get' . $methodSuffix;
        $property->setter = 'set' . $methodSuffix;
        $property->dropper = 'drop' . $methodSuffix;

        if ($columnName) {
            $property->columnName = $columnName;
        } else {
            $property->columnName = $name;
        }

        $property->type = $type;
        $property->className = $className;

        if ($size) {
            if (($type == 'integer') || ($type == 'identifier') // obsoleted
                || ($type == 'integerIdentifier') || ($type == 'enumeration') || ($type == 'enum') || ($type == 'registry')
            ) {
                $property->min = self::$limits[$size][0];
                $property->max = self::$limits[$size][1];
            } elseif ($type == 'scalarIdentifier') {
                // supported only in master
            } elseif ($type != 'float') { // string
                $property->max = $size;
            }

            $property->size = $size;
        }

        $property->required = $required;
        $property->generic = $generic;
        $property->inner = $inner;

        $property->relationId = $relationId;
        $property->strategyId = $strategyId;

        $property->identifier = $generic && $required && (($type == 'identifier') // obsoleted
                || ($type == 'integerIdentifier') || ($type == 'scalarIdentifier') || ($type == 'uuidIdentifier'));

        return $property;
    }

    public function getName() {
        return $this->name;
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function getGetter() {
        return $this->getter;
    }

    public function getSetter() {
        return $this->setter;
    }

    public function getDropper() {
        return $this->dropper;
    }

    /**
     * @return LightMetaProperty
     **/
    public function setColumnName($name) {
        $this->columnName = $name;

        return $this;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getMin() {
        return $this->min;
    }

    public function getMax() {
        return $this->max;
    }

    public function getType() {
        return $this->type;
    }

    public function isRequired() {
        return $this->required;
    }

    /**
     * @return LightMetaProperty
     **/
    public function setRequired($yrly) {
        $this->required = $yrly;

        return $this;
    }

    public function isGenericType() {
        return $this->generic;
    }

    public function isInner() {
        return $this->inner;
    }

    public function getRelationId() {
        return $this->relationId;
    }

    public function getFetchStrategyId() {
        return $this->strategyId;
    }

    /**
     * @return LightMetaProperty
     **/
    public function setFetchStrategy(FetchStrategy $strategy) {
        $this->strategyId = $strategy->getId();

        return $this;
    }

    /**
     * @return LightMetaProperty
     **/
    public function dropFetchStrategy() {
        $this->strategyId = null;

        return $this;
    }

    public function getContainerName($holderName) {
        return $holderName . ucfirst($this->getName()) . 'DAO';
    }

    public function isBuildable($array, $prefix = null) {
        $column = $prefix . $this->columnName;
        $exists = isset($array[$column]);

        if ($this->relationId || $this->generic) {
            // skip collections
            if (($this->relationId <> MetaRelation::ONE_TO_ONE) && !$this->generic) {
                return false;
            }

            if ($this->required) {
                Assert::isTrue($exists, 'required property not found: ' . $this->name);
            } elseif (!$exists) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return BasePrimitive
     **/
    public function makePrimitive($name) {
        $prm = call_user_func([Primitive::class, $this->type], $name);

        if (null !== ($min = $this->getMin())) {
            $prm->setMin($min);
        }

        if (null !== ($max = $this->getMax())) {
            $prm->setMax($max);
        }

        if ($prm instanceof IdentifiablePrimitive) {
            $prm->of($this->className);
        }

        if ($this->required) {
            $prm->required();
        }

        return $prm;
    }

    public function fillMapping(array $mapping) {
        if (!$this->relationId || ($this->relationId == MetaRelation::ONE_TO_ONE) || ($this->strategyId == FetchStrategy::LAZY)) {
            $mapping[$this->name] = $this->columnName;
        }

        return $mapping;
    }

    /**
     * @return Form
     **/
    public function fillForm(Form $form, $prefix = null) {
        return $form->add($this->makePrimitive($prefix . $this->name));
    }

    /**
     * @return InsertOrUpdateQuery
     **/
    public function fillQuery(InsertOrUpdateQuery $query, Prototyped $object, Prototyped $old = null) {
        if ($this->relationId || $this->generic) {
            // skip collections
            if (($this->relationId <> MetaRelation::ONE_TO_ONE) && !$this->generic) {
                return $query;
            }

            $getter = $this->getter;

            if ($this->relationId && $this->strategyId == FetchStrategy::LAZY) {
                $getter = $getter . 'Id';
            }

            $value = $object->{$getter}();
            if ($old) {
                $oldValue = $old->{$getter}();
                if ($oldValue === null && $value === $oldValue) {
                    return $query;
                } elseif ($this->relationId && $this->strategyId == FetchStrategy::LAZY && ($value === $oldValue)) {
                    return $query;
                } elseif ($value instanceof Identifiable && $oldValue instanceof Identifiable && $value->getId() === $oldValue->getId()) {
                    return $query;
                } elseif (serialize($value) == serialize($oldValue)) {
                    return $query;
                }
            }


            switch ($this->type) {
                case 'binary':
                    $query->set($this->columnName, new DBBinary($value));
                    break;
                case 'json' :
                    $query->set($this->columnName, DBArray::create($value)->json());
                    break;
                case 'jsonb' :
                    $query->set($this->columnName, DBArray::create($value)->jsonb());
                    break;
                default:
                    $query->lazySet($this->columnName, $value);
                    break;
            }
        }

        return $query;
    }

    public function toValue(ProtoDAO $dao = null, $array, $prefix = null) {
        $raw = $array[$prefix . $this->columnName];

        if ($this->type == 'binary') {
            return DBPool::getByDao($dao)->getDialect()->unquoteBinary($raw);
        }

        if ($this->className == 'HttpUrl') {
            return HttpUrl::create()->parse($raw);
        }

        if ($this->type === 'json' || $this->type === 'jsonb') {
            return json_decode($raw, true); //associative array instead of object
        }

        if (!$this->identifier && $this->generic && $this->className) {
            return call_user_func([$this->className, 'create'], $raw);
        } elseif (!$this->identifier && $this->className) {
            // BOVM: prevents segfault on >=php-5.2.5
            Assert::classExists($this->className);

            if (
                !is_subclass_of($this->className, Enumeration::class) &&
                !is_subclass_of($this->className, Enum::class) &&
                !is_subclass_of($this->className, Registry::class)
            ) {
                $remoteDao = call_user_func([$this->className, 'dao']);

                $joinPrefix = $remoteDao->getJoinPrefix($this->columnName, $prefix);

                $joined = (($this->strategyId == FetchStrategy::JOIN) || isset($array[$joinPrefix . $remoteDao->getIdName()]));

                if ($joined) {
                    return $remoteDao->makeObject($array, $joinPrefix);
                } else {
                    // will be fetched later
                    // by AbstractProtoClass::fetchEncapsulants
                    $object = new $this->className;
                    $object->setId($raw);

                    return $object;
                }
            } else {
                return new $this->className($raw);
            }
        }

        // veeeeery "special" handling, by tradition.
        // MySQL returns 0/1, others - t/f
        if ($this->type == 'boolean') {
            return (bool)strtr($raw, ['f' => null]);
        }

        return $raw;
    }

    public function isIdentifier() {
        return $this->identifier;
    }

    final public function toString() {
        return '\\' . get_class($this) . '::fill(' . 'new ' . '\\' . get_class($this) . '(), ' . "'{$this->name}', " . (($this->columnName <> $this->name) ? "'{$this->columnName}'" : 'null') . ', ' . "'{$this->type}', " . ($this->className ? "'{$this->className}'" : 'null') . ', ' . ($this->size ? $this->size : 'null') . ', ' . ($this->required ? 'true' : 'false') . ', ' . ($this->generic ? 'true' : 'false') . ', ' . ($this->inner ? 'true' : 'false') . ', ' . ($this->relationId ? $this->relationId : 'null') . ', ' . ($this->strategyId ? $this->strategyId : 'null') . ')';
    }

    public function isFormless() {
        // NOTE: enum here formless types
        return in_array($this->type, ['enumeration', 'enum', 'registry',]);
    }
}
