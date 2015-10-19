<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Core\Base\Prototyped;
use Hesper\Core\Base\Singleton;
use Hesper\Main\DAO\DAOConnected;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassType;
use Hesper\Meta\Pattern\AbstractClassPattern;
use Hesper\Meta\Pattern\InternalClassPattern;

/**
 * Class BusinessClassBuilder
 * @package Hesper\Meta\Builder
 */
final class BusinessClassBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();
		$uses = [Singleton::class, $class->getAutoBusinessClass()];

		if ($type = $class->getType()) {
			$typeName = $type->toString() . ' ';
		} else {
			$typeName = null;
		}

		$interfaces = ' implements Prototyped';
		$uses[] = Prototyped::class;
		$uses[] = $class->getProtoClass();

		if (
			$class->getPattern()->daoExists() &&
			(!$class->getPattern() instanceof AbstractClassPattern)
		) {
			$interfaces .= ', DAOConnected';
			$uses[] = DAOConnected::class;

			$daoName = $class->getName() . 'DAO';
			$uses[] = $class->getDaoClass();

			$dao = <<<EOT
	/**
	 * @return {$daoName}
	**/
	public static function dao()
	{
		return Singleton::getInstance({$daoName}::class);
	}

EOT;
		} else {
			$dao = null;
		}

		$out .= <<<EOT
namespace {$class->getNamespace()}\Business;


EOT;

		foreach($uses as $import) {
			$out .= <<<EOT
use $import;

EOT;
		}

		$out .= <<<EOT

{$typeName}class {$class->getName()} extends Auto{$class->getName()}{$interfaces}
{
EOT;

		if (!$type || $type->getId() !== MetaClassType::CLASS_ABSTRACT) {
			$customCreate = null;

			if ($class->getFinalParent()
			          ->getPattern() instanceof InternalClassPattern
			) {
				$parent = $class;

				while ($parent = $parent->getParent()) {
					$info = new \ReflectionClass($parent->getName());

					if ($info->hasMethod('create') && ($info->getMethod('create')
					                                        ->getParameters() > 0)
					) {
						$customCreate = true;
						break;
					}
				}
			}

			if ($customCreate) {
				$creator = $info->getMethod('create');

				$declaration = [];

				foreach ($creator->getParameters() as $parameter) {
					$declaration[] = '$' . $parameter->getName() // no one can live without default value @ ::create
						. ' = ' . ($parameter->getDefaultValue() ? $parameter->getDefaultValue() : 'null');
				}

				$declaration = implode(', ', $declaration);

				$out .= <<<EOT

	/**
	 * @return {$class->getName()}
	**/
	public static function create({$declaration})
	{
		return new self({$declaration});
	}
		
EOT;
			} else {
				$out .= <<<EOT

	/**
	 * @return {$class->getName()}
	**/
	public static function create()
	{
		return new self;
	}
		
EOT;
			}

			$protoName = 'Proto' . $class->getName();

			$out .= <<<EOT

{$dao}
	/**
	 * @return {$protoName}
	**/
	public static function proto()
	{
		return Singleton::getInstance({$protoName}::class);
	}

EOT;

		}

		$out .= <<<EOT

	// your brilliant stuff goes here
}

EOT;

		return $out . self::getHeel();
	}
}
