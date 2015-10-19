<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladlen Y. Koshelev
 */
namespace Hesper\Main\Flow;

/**
 * Class DecoratorController
 * @package Hesper\Main\Flow
 */
abstract class DecoratorController implements Controller {

	protected $inner = null;

	public function __construct(Controller $inner) {
		$this->inner = $inner;
	}

	/**
	 * @return ModelAndView
	 **/
	public function handleRequest(HttpRequest $request) {
		return $this->inner->handleRequest($request);
	}
}
