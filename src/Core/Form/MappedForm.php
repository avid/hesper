<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Main\Base\RequestType;
use Hesper\Main\Flow\HttpRequest;

/**
 * Class MappedForm
 * @package Hesper\Core\Form
 */
final class MappedForm {

	private $form = null;
	private $type = null;

	private $map = [];

	/**
	 * @return MappedForm
	 **/
	public static function create(Form $form) {
		return new self($form);
	}

	public function __construct(Form $form) {
		$this->form = $form;
	}

	/**
	 * @return Form
	 **/
	public function getForm() {
		return $this->form;
	}

	/**
	 * @return MappedForm
	 **/
	public function setDefaultType(RequestType $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return MappedForm
	 **/
	public function addSource($primitiveName, RequestType $type) {
		$this->checkExistence($primitiveName);

		$this->map[$primitiveName][] = $type;

		return $this;
	}

	/**
	 * @return MappedForm
	 **/
	public function importOne($name, HttpRequest $request) {
		$this->checkExistence($name);

		$scopes = [];

		if (isset($this->map[$name])) {
			foreach ($this->map[$name] as $type) {
				$scopes[] = $request->getByType($type);
			}
		} elseif ($this->type) {
			$scopes[] = $request->getByType($this->type);
		}

		$first = true;
		foreach ($scopes as $scope) {
			if ($first) {
				$this->form->importOne($name, $scope);
				$first = false;
			} else {
				$this->form->importOneMore($name, $scope);
			}
		}

		return $this;
	}

	/**
	 * @return MappedForm
	 **/
	public function import(HttpRequest $request) {
		foreach ($this->form->getPrimitiveNames() as $name) {
			$this->importOne($name, $request);
		}

		$this->form->checkRules();

		return $this;
	}

	/**
	 * @return MappedForm
	 **/
	public function export(RequestType $type) {
		$result = [];

		$default = ($this->type == $type);

		foreach ($this->form->getPrimitiveList() as $name => $prm) {
			if ((isset($this->map[$name]) && in_array($type, $this->map[$name])) || (!isset($this->map[$name]) && $default)) {
				if ($prm->getValue()) {
					$result[$name] = $prm->exportValue();
				}
			}
		}

		return $result;
	}

	/**
	 * @return MappedForm
	 **/
	private function checkExistence($name) {
		if (!$this->form->primitiveExists($name)) {
			throw new MissingElementException("there is no '{$name}' primitive");
		}

		return $this;
	}
}
