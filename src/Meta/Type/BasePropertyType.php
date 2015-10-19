<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassProperty;

/**
 * Class BasePropertyType
 * @package Hesper\Meta\Type
 */
abstract class BasePropertyType {

	abstract public function getDeclaration();

	abstract public function isMeasurable();

	abstract public function toColumnType();

	abstract public function getPrimitiveName();

	protected $default = null;

	public function isGeneric() {
		return true;
	}

	public function toMethods(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null) {
		return $this->toGetter($class, $property, $holder) . $this->toSetter($class, $property, $holder);
	}

	public function hasDefault() {
		return ($this->default !== null);
	}

	public function getDefault() {
		return $this->default;
	}

	public function setDefault($default) {
		throw new UnsupportedMethodException('only generic non-object types can have default values atm');
	}

	public function toGetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null) {
		if ($holder) {
			$name = $holder->getName() . '->get' . ucfirst($property->getName()) . '()';
		} else {
			$name = $property->getName();
		}

		$methodName = 'get' . ucfirst($property->getName());

		return <<<EOT

public function {$methodName}()
{
	return \$this->{$name};
}

EOT;
	}

	public function toSetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null) {
		$name = $property->getName();
		$methodName = 'set' . ucfirst($name);

		if ($holder) {
			return <<<EOT

/**
 * @return {$holder->getClass()->getName()}
**/
public function {$methodName}(\${$name})
{
	\$this->{$holder->getName()}->{$methodName}(\${$name});

	return \$this;
}

EOT;
		} else {
			return <<<EOT

/**
 * @return {$class->getName()}
**/
public function {$methodName}(\${$name})
{
	\$this->{$name} = \${$name};

	return \$this;
}

EOT;
		}
	}

	public function getHint() {
		return null;
	}
}
