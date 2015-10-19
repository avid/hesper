<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive;

/**
 * Class MethodMappedController
 * @package Hesper\Main\Flow
 */
abstract class MethodMappedController implements Controller {

	private $methodMap     = [];
	private $defaultAction = null;

	/**
	 * @return ModelAndView
	 **/
	public function handleRequest(HttpRequest $request) {
		if ($action = $this->chooseAction($request)) {

			$method = $this->methodMap[$action];
			/** @var ModelAndView $mav */
			$mav = $this->{$method}($request);

			if ($mav->viewIsRedirect()) {
				return $mav;
			}

			$mav->getModel()->set('chosenAction', $action);

			return $mav;

		} else {
			return ModelAndView::create();
		}
	}

	public function chooseAction(HttpRequest $request) {
		$action = Primitive::choice('action')->setList($this->methodMap);

		if ($this->getDefaultAction()) {
			$action->setDefault($this->getDefaultAction());
		}

		Form::create()->add($action)->import($request->getGet())->importMore($request->getPost())->importMore($request->getAttached());

		if (!$command = $action->getValue()) {
			return $action->getDefault();
		}

		return $command;
	}

	/**
	 * @return MethodMappedController
	 **/
	public function setMethodMapping($action, $methodName) {
		$this->methodMap[$action] = $methodName;

		return $this;
	}

	/**
	 * @return MethodMappedController
	 **/
	public function dropMethodMapping($action) {
		unset($this->methodMap[$action]);

		return $this;
	}

	public function getMethodMapping() {
		return $this->methodMap;
	}

	/**
	 * @return MethodMappedController
	 **/
	public function setDefaultAction($action) {
		$this->defaultAction = $action;

		return $this;
	}

	/**
	 * @return MethodMappedController
	 **/
	public function setMethodMappingList($array) {
		foreach ($array as $action => $methodName) {
			$this->setMethodMapping($action, $methodName);
		}

		return $this;
	}

	public function getDefaultAction() {
		return $this->defaultAction;
	}
}
