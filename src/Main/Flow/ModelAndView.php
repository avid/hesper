<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Assert;
use Hesper\Main\UI\View\CleanRedirectView;
use Hesper\Main\UI\View\View;

/**
 * @ingroup Flow
 **/
class ModelAndView {

	/** @var Model|null */
	private $model = null;

	/** @var View|null */
	private $view = null;

	/**
	 * @return ModelAndView
	 **/
	public static function create() {
		return new self;
	}

	public function __construct() {
		$this->model = new Model();
	}

	/**
	 * @return Model
	 **/
	public function getModel() {
		return $this->model;
	}

	/**
	 * @return ModelAndView
	 **/
	public function setModel(Model $model) {
		$this->model = $model;

		return $this;
	}

	public function getView() {
		return $this->view;
	}

	/**
	 * @return ModelAndView
	 **/
	public function setView($view) {
		Assert::isTrue(($view instanceof View) || is_string($view), 'do not know, what to do with such view');

		$this->view = $view;

		return $this;
	}

	public function dropView() {
		$this->view = null;

		return $this;
	}


	public function viewIsRedirect() {
		return ($this->view instanceof CleanRedirectView) || (is_string($this->view) && strpos($this->view, 'redirect') === 0);
	}

	public function viewIsNormal() {
		return (!$this->viewIsRedirect() && $this->view !== View::ERROR_VIEW);
	}
}
