<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Primitive\BasePrimitive;
use Hesper\Core\Form\Primitive\FiltrablePrimitive;
use Hesper\Core\Form\Primitive\ListedPrimitive;

/**
 * Common Primitive-handling.
 * @package Hesper\Core\Form
 */
abstract class PlainForm {

	protected $primitives = [];

	/**
	 * @return Form
	 **/
	public function clean() {
		foreach ($this->primitives as $prm) {
			$prm->clean();
		}

		return $this;
	}

	public function exists($name) {
		return isset($this->primitives[$name]);
	}

	/**
	 * @deprecated, use exists()
	 **/
	public function primitiveExists($name) {
		return $this->exists($name);
	}

	/**
	 * @throws WrongArgumentException
	 * @return Form
	 **/
	public function add(BasePrimitive $prm) {
		$name = $prm->getName();

		Assert::isFalse(isset($this->primitives[$name]), 'i am already exists!');

		$this->primitives[$name] = $prm;

		return $this;
	}

	/**
	 * @return Form
	 **/
	public function set(BasePrimitive $prm) {
		$this->primitives[$prm->getName()] = $prm;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return Form
	 **/
	public function drop($name) {
		if (!isset($this->primitives[$name])) {
			throw new MissingElementException("can not drop inexistent primitive '{$name}'");
		}

		unset($this->primitives[$name]);

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return BasePrimitive
	 **/
	public function get($name) {
		if (isset($this->primitives[$name])) {
			return $this->primitives[$name];
		}

		throw new MissingElementException("knows nothing about '{$name}'");
	}

	public function getValue($name) {
		return $this->get($name)->getValue();
	}

	public function setValue($name, $value) {
		$this->get($name)->setValue($value);

		return $this;
	}

	public function getRawValue($name) {
		return $this->get($name)->getRawValue();
	}

	public function getValueOrDefault($name) {
		return $this->get($name)->getValueOrDefault();
	}

	/**
	 * @deprecated since version 1.0
	 * @see        getValueOrDefault
	 */
	public function getActualValue($name) {
		return $this->get($name)->getActualValue();
	}

	public function getSafeValue($name) {
		return $this->get($name)->getSafeValue();
	}

	public function getChoiceValue($name) {
		Assert::isTrue(($prm = $this->get($name)) instanceof ListedPrimitive);

		return $prm->getChoiceValue();
	}

	public function getActualChoiceValue($name) {
		Assert::isTrue(($prm = $this->get($name)) instanceof ListedPrimitive);

		return $prm->getActualChoiceValue();
	}

	public function getDisplayValue($name) {
		$primitive = $this->get($name);

		if ($primitive instanceof FiltrablePrimitive) {
			return $primitive->getDisplayValue();
		} else {
			return $primitive->getActualValue();
		}
	}

	public function getPrimitiveNames() {
		return array_keys($this->primitives);
	}

	/**
	 * @return BasePrimitive[]
	 */
	public function getPrimitiveList() {
		return $this->primitives;
	}
}
	