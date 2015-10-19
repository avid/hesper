<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Assert;
use Hesper\Main\Flow\Model;

/**
 * Class PartViewer
 * @package Hesper\Main\UI\View
 */
class PartViewer {

	protected $viewResolver = null;
	protected $model        = null;

	public function __construct(ViewResolver $resolver, $model = null) {
		$this->viewResolver = $resolver;
		$this->model = $model;
	}

	/**
	 * @return PartViewer
	 **/
	public function view($partName, Model $model = null) {
		Assert::isTrue($model === null || $model instanceof Model);

		// use model from outer template if none specified
		if ($model === null) {
			$model = $this->model;

			$parentModel = $this->model->has('parentModel') ? $this->model->get('parentModel') : null;

		} else {
			$parentModel = $this->model;
		}

		$model->set('parentModel', $parentModel);

		$rootModel = $this->model->has('rootModel') ? $this->model->get('rootModel') : $this->model;

		$model->set('rootModel', $rootModel);

		if ($partName instanceof View) {
			$partName->render($model);
		} else {
			$this->viewResolver->resolveViewName($partName)->render($model);
		}

		return $this;
	}

	public function toString($partName, $model = null) {
		try {
			ob_start();
			$this->view($partName, $model);

			return ob_get_clean();
		} catch (\Exception $e) {
			ob_end_clean();
			throw $e;
		}
	}

	public function partExists($partName) {
		return $this->viewResolver->viewExists($partName);
	}

	/**
	 * @return Model
	 **/
	public function getModel() {
		return $this->model;
	}
}
