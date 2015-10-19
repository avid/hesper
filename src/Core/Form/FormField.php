<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form;

/**
 * Atom for using in LogicalExpression.
 * @see     DBField
 * @package Hesper\Core\Form
 */
final class FormField {

	private $primitiveName = null;

	public function __construct($name) {
		$this->primitiveName = $name;
	}

	/**
	 * @return FormField
	 **/
	public static function create($name) {
		return new self($name);
	}

	public function getName() {
		return $this->primitiveName;
	}

	public function toValue(Form $form) {
		return $form->getValue($this->primitiveName);
	}
}
