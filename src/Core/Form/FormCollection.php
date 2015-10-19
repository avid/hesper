<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\Assert;

/**
 * Class FormCollection
 * @package Hesper\Core\Form
 */
final class FormCollection implements \Iterator {

	/**
	 * @var Form
	 */
	private $sampleForm = null;

	private $primitiveNames = [];

	private $imported = false;

	private $formList = [];

	/**
	 * @param Form $sample
	 *
	 * @return FormCollection
	 */
	public static function create(Form $sample) {
		return new self($sample);
	}


	public function __construct(Form $sample) {
		$this->sampleForm = $sample;
	}

	/**
	 * @param array $scope
	 * from http request
	 * looks like foo[1]=42&bar[1]=test&foo[2]=44&bar[2]=anothertest
	 */
	public function import(array $scope) {
		$this->imported = true;

		foreach ($scope as $name => $paramList) {

			/**
			 * @var array $paramList
			 * looks like array(1 => 42, 2 => 44)
			 */
			Assert::isArray($paramList);

			foreach ($paramList as $key => $value) {
				if (!isset($this->formList[$key])) {
					$this->formList[$key] = clone $this->sampleForm;
				}
				$this->formList[$key]->importMore([$name => $value]);
			}
		}

		reset($this->formList);

		return $this;
	}

	public function current() {
		Assert::isTrue($this->imported, "Import scope in me before try to iterate");

		return current($this->formList);
	}

	public function key() {
		Assert::isTrue($this->imported, "Import scope in me before try to iterate");

		return key($this->formList);
	}

	public function next() {
		Assert::isTrue($this->imported, "Import scope in me before try to iterate");

		return next($this->formList);
	}

	public function rewind() {
		Assert::isTrue($this->imported, "Import scope in me before try to iterate");

		return reset($this->formList);
	}

	public function valid() {
		Assert::isTrue($this->imported, "Import scope in me before try to iterate");

		return (key($this->formList) !== null);
	}
}