<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexander A. Klestoff
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Assert;
use Hesper\Main\UI\View\MultiPrefixPhpViewResolver;
use Hesper\Main\UI\View\RedirectView;
use Hesper\Main\Util\ClassUtils;
use Hesper\Main\Util\Router\Router;
use Hesper\Main\Util\Router\RouterRegexpRule;
use Hesper\Main\Util\Router\RouterRewrite;

/**
 * Class SimpleFrontController
 * @package Hesper\Main\Flow
 */
class SimpleFrontController implements Controller {

	const DEFAULT_CONTROLLER = 'main';
	const DEFAULT_TEMPLATE   = 'main';
	const DEFAULT_ACTION     = 'show';
	//LIKE /controller/42/action.html
	const ROUTE_REGEXP = '(\w+)?((/(\d+))?(/(\w+)))?(\.(.*))?';

	const DEFAULT_ROUTE_NAME = '*';

	const DEFAULT_FORMAT = 'html';


	protected $allowedFormatList = [self::DEFAULT_FORMAT];

	/**
	 * @var HttpRequest
	 */
	protected $request        = null;
	private   $controllerName = null;

	private $templatesDirectory = null;

	/**
	 * @return SimpleFrontController
	 */
	public static function create($templatesDirectory) {
		return new static($templatesDirectory);
	}

	public function __construct($templatesDirectory) {
		$this->templatesDirectory = $templatesDirectory;
	}

	public function handleRequest(HttpRequest $request) {
		$this->request = $request;

		$this->getRouter()->route($request);

		$this->prepareResponseFormat($request);

		$this->handleMav($this->makeControllerChain()->handleRequest($request));
	}

	/**
	 * @return Router
	 */
	protected function getRouter() {
		return RouterRewrite::me()->addRoute(self::DEFAULT_ROUTE_NAME, RouterRegexpRule::create(self::ROUTE_REGEXP)->setMap([1 => 'area', 4 => 'id', 6 => 'action', 8 => 'format',])->setDefaults(['area' => self::DEFAULT_CONTROLLER, 'action' => self::DEFAULT_ACTION, 'id' => 0, 'format' => self::DEFAULT_FORMAT]));
	}

	protected function prepareResponseFormat() {
		if ($this->request->hasAttachedVar('format')) {
			Assert::isNotFalse(array_search($this->request->getAttachedVar('format'), $this->allowedFormatList));

		} else {
			$this->request->setAttachedVar('format', self::DEFAULT_FORMAT);
		}
	}

	/**
	 * @return Controller
	 */
	protected function makeControllerChain() {
		$this->controllerName = self::DEFAULT_CONTROLLER;

		if ($this->request->hasAttachedVar('area') && $this->request->getAttachedVar('area') && ClassUtils::isClassName($this->request->getAttachedVar('area'))) {
			$this->controllerName = $this->request->getAttachedVar('area');
		}

		return new $this->controllerName;
	}

	protected function handleMav(ModelAndView $mav) {
		$view = $mav->getView() ?: self::DEFAULT_TEMPLATE;
		$model = $mav->getModel();

		if (!$view instanceof RedirectView) {
			$model->set('area', $this->controllerName);
		}

		if (is_string($view)) {
			if ($view == $this->controllerName) {
				$view = self::DEFAULT_TEMPLATE;
			}

			$viewResolver = $this->getViewResolver();

			foreach ($this->getTemplatePathList() as $templatePath) {
				$viewResolver->addPrefix($templatePath);
			}

			$view = $viewResolver->resolveViewName($view);
		}

		$view->render($model);
	}

	protected function getTemplatePathList() {
		return [$this->templatesDirectory . $this->request->getAttachedVar('format') . '/' . $this->controllerName . '/', $this->templatesDirectory . $this->request->getAttachedVar('format') . '/'];
	}

	protected function getViewResolver() {
		return MultiPrefixPhpViewResolver::create()->setViewClassName('SimplePhpView');
	}
}