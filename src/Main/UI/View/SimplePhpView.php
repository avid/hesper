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
 * @ingroup Flow
 **/
class SimplePhpView extends EmptyView {

	protected $templatePath     = null;
	protected $partViewResolver = null;

	public function __construct($templatePath, ViewResolver $partViewResolver) {
		$this->templatePath = $templatePath;
		$this->partViewResolver = $partViewResolver;
	}

	/**
	 * @return SimplePhpView
	 **/
	public function render(Model $model = null) {
		Assert::isTrue($model === null || $model instanceof Model);

		if ($model) {
			extract($model->getList());
		}

		$partViewer = new PartViewer($this->partViewResolver, $model);

		$this->preRender();

		include $this->templatePath;

		$this->postRender();

		return $this;
	}

	public function toString($model = null) {
		try {
			ob_start();
			$this->render($model);

			return ob_get_clean();
		} catch (\Exception $e) {
			ob_end_clean();
			throw $e;
		}
	}

	/**
	 * @return SimplePhpView
	 **/
	protected function preRender() {
		return $this;
	}

	/**
	 * @return SimplePhpView
	 **/
	protected function postRender() {
		return $this;
	}
}
