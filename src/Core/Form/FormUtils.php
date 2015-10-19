<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Form\Primitive\ListedPrimitive;
use Hesper\Core\Form\Primitive\PrimitiveNoValue;

/**
 * Class FormUtils
 * @package Hesper\Core\Form
 */
final class FormUtils extends StaticFactory {

	/* void */
	public static function object2form($object, Form $form, $ignoreNull = true) {
		Assert::isTrue(is_object($object));

		$primitives = $form->getPrimitiveList();

		if ($object instanceof Prototyped) {
			$proto = $object->proto();

			foreach (array_keys($proto->getExpandedPropertyList()) as $name) {
				if ($form->exists($name)) {
					$proto->importPrimitive($name, $form, $form->get($name), $object, $ignoreNull);
				}
			}
		} else {
			$class = new \ReflectionClass($object);

			foreach ($class->getProperties() as $property) {
				$name = $property->getName();

				if (isset($primitives[$name])) {
					$getter = 'get' . ucfirst($name);
					if ($class->hasMethod($getter)) {
						$value = $object->$getter();
						if (!$ignoreNull || ($value !== null)) {
							$form->importValue($name, $value);
						}
					}
				}
			}
		}
	}

	/* void */
	public static function form2object(Form $form, $object, $ignoreNull = true) {
		Assert::isTrue(is_object($object));

		if ($object instanceof Prototyped) {
			$proto = $object->proto();
			$list = $proto->getExpandedPropertyList();

			foreach ($form->getPrimitiveList() as $name => $prm) {
				if (isset($list[$name])) {
					$proto->exportPrimitive($name, $prm, $object, $ignoreNull);
				}
			}
		} else {
			$class = new \ReflectionClass($object);

			foreach ($form->getPrimitiveList() as $name => $prm) {
				$setter = 'set' . ucfirst($name);

				if ($prm instanceof ListedPrimitive) {
					$value = $prm->getChoiceValue();
				} else {
					$value = $prm->getValue();
				}

				if ($class->hasMethod($setter) && (!$ignoreNull || ($value !== null))) {
					if ( // magic!
						$prm->getName() == 'id' && ($value instanceof Identifiable)
					) {
						$value = $value->getId();
					}

					if ($value === null) {
						$dropper = 'drop' . ucfirst($name);

						if ($class->hasMethod($dropper)) {
							$object->$dropper();
							continue;
						}
					}

					$object->$setter($value);
				}
			}
		}
	}

	public static function checkPrototyped(Prototyped $object) {
		$form = $object->proto()->makeForm();

		self::object2form($object, $form, false);

		return $form->getErrors();
	}

	/**
	 * @return Form
	 */
	public static function removePrefix(Form $form, $prefix) {
		$newForm = Form::create();

		foreach ($form->getPrimitiveList() as $primitive) {
			$primitive->setName(strtr($primitive->getName(), [$prefix => '']));

			$newForm->add($primitive);
		}

		return $newForm;
	}

}
