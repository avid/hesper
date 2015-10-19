<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\LogicalObject;

/**
 * Rules support for final Form.
 * @package Hesper\Core\Form
 */
abstract class RegulatedForm extends PlainForm {

	protected $rules    = []; // forever
	protected $violated = []; // rules

	/**
	 * @throws WrongArgumentException
	 * @return Form
	 **/
	public function addRule($name, LogicalObject $rule) {
		Assert::isString($name);

		$this->rules[$name] = $rule;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return Form
	 **/
	public function dropRuleByName($name) {
		if (isset($this->rules[$name])) {
			unset($this->rules[$name]);

			return $this;
		}

		throw new MissingElementException("no such rule with '{$name}' name");
	}

	public function ruleExists($name) {
		return isset($this->rules[$name]);
	}

	/**
	 * @return Form
	 **/
	public function checkRules() {
		foreach ($this->rules as $name => $logicalObject) {
			if (!$logicalObject->toBoolean($this)) {
				$this->violated[$name] = Form::WRONG;
			}
		}

		return $this;
	}
}
