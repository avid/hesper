<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive\PrimitiveForm;
use Hesper\Main\EntityProto\Accessor\ObjectGetter;
use Hesper\Main\Util\ClassUtils;

class EntityProto extends Singleton {

	const PROTO_CLASS_PREFIX = 'EntityProto';

	public function baseProto() {
		return null;
	}

	public function className() {
		return null;
	}

	// TODO: think about anonymous primitives and persistant mapping
	// instead of creating new one on each call
	public function getFormMapping() {
		return [];
	}

	// TODO: use checkConstraints($object, $previousObject = null)
	// where object may be business object, form, scope, etc.
	// NOTE: object may contain errors already
	public function checkConstraints($object, Form $form, $previousObject = null) {
		return $this;
	}

	public function isAbstract() {
		return false;
	}

	public function isInstanceOf(EntityProto $proto) {
		return ClassUtils::isInstanceOf($this->className(), $proto->className());
	}

	final public function getFullFormMapping() {
		$result = $this->getFormMapping();

		if ($this->baseProto()) {
			$result = $result + $this->baseProto()->getFullFormMapping();
		}

		return $result;
	}

	final public function validate($object, $form, $previousObject = null) {
		if (is_array($object)) {
			return $this->validateList($object, $form, $previousObject);
		}

		Assert::isInstance($object, $this->className());
		Assert::isInstance($form, Form::class);

		if ($previousObject) {
			Assert::isInstance($previousObject, $this->className());
		}

		if ($this->baseProto()) {
			$this->baseProto()->validate($object, $form, $previousObject);
		}

		return $this->validateSelf($object, $form, $previousObject);
	}

	final public function validateSelf($object, $form, $previousObject = null) {
		$this->checkConstraints($object, $form, $previousObject);

		$getter = new ObjectGetter($this, $object);

		$previousGetter = $previousObject ? new ObjectGetter($this, $previousObject) : null;

		foreach ($this->getFormMapping() as $id => $primitive) {

			if ($primitive instanceof PrimitiveForm) {
				$proto = $primitive->getProto();

				$childForm = $form->getValue($primitive->getName());

				$child = $getter->get($id);

				$previousChild = $previousGetter ? $previousGetter->get($id) : null;

				$childResult = true;

				if ($child && !$proto->validate($child, $childForm, $previousChild)) {
					$form->markWrong($primitive->getName());
				}
			}
		}

		$errors = $form->getErrors();

		return empty($errors);
	}

	final public function validateList($objectsList, $formsList, $previousObjectsList = null) {
		Assert::isEqual(count($objectsList), count($formsList));

		reset($formsList);

		if ($previousObjectsList) {
			Assert::isEqual(count($objectsList), count($previousObjectsList));

			reset($previousObjectsList);
		}

		$result = true;

		$previousObject = null;

		foreach ($objectsList as $object) {

			$form = current($formsList);
			next($formsList);

			if ($previousObjectsList) {
				$previousObject = current($previousObjectsList);
				next($previousObjectsList);
			}

			if (!$this->validate($object, $form, $previousObject)) {
				$result = false;
			}
		}

		return $result;
	}

	final public function createObject() {
		$className = $this->className();

		return new $className;
	}

	/**
	 * @return Form
	 * @deprecated you should use PrototypedBuilder to make forms
	 **/
	final public function makeForm() {
		return $this->attachPrimitives($this->baseProto() ? $this->baseProto()->makeForm() : Form::create());
	}

	/**
	 * @return Form
	 **/
	final public function attachPrimitives(Form $form) {
		foreach ($this->getFormMapping() as $primitive) {
			$form->add($primitive);
		}

		return $form;
	}

	final public function getOwnPrimitive($name) {
		$mapping = $this->getFormMapping();

		if (!isset($mapping[$name])) {
			throw new WrongArgumentException("i know nothing about property '$name'");
		}

		return $mapping[$name];
	}

	final public function getPrimitive($name) {
		try {
			$result = $this->getOwnPrimitive($name);

		} catch (WrongArgumentException $e) {

			if (!$this->baseProto()) {
				throw $e;
			}

			$result = $this->baseProto()->getPrimitive($name);
		}

		return $result;
	}
}
