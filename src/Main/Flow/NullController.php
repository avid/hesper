<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov, Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

/**
 * Class NullController
 * @package Hesper\Main\Flow
 */
final class NullController implements Controller {

	private $model = null;

	/**
	 * @return NullController
	 **/
	public static function create(Model $model = null) {
		return new self($model);
	}

	public function __construct(Model $model = null) {
		$this->model = $model;
	}

	/**
	 * @return ModelAndView
	 **/
	public function handleRequest(HttpRequest $request) {
		$result = ModelAndView::create();

		if ($this->model) {
			$result->setModel($this->model);
		}

		return $result;
	}
}
