<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry V. Sokolov, Denis M. Gabaidulin
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Assert;
use Hesper\Main\Base\RequestType;

/**
 * Class ProxyController
 * @package Hesper\Main\Flow
 * @TODO: add action => requestType mapper
 */
final class ProxyController implements Controller {

	private $innerController = null;
	private $request         = null;
	private $requestType     = null;
	private $requestGetter   = null;

	private static $requestGetterMap = [RequestType::ATTACHED => 'Attached', RequestType::GET => 'Get', RequestType::POST => 'Post'];

	/**
	 * @return ProxyController
	 **/
	public static function create() {
		return new self;
	}

	public function __construct() {
		$this->requestType = RequestType::post();
	}

	/**
	 * @return ProxyController
	 **/
	public function setInner(Controller $controller) {
		$this->innerController = $controller;

		return $this;
	}

	/**
	 * @return Controller
	 **/
	public function getInner() {
		return $this->innerController;
	}

	public function getName() {
		return get_class($this->innerController);
	}

	/**
	 * @return ModelAndView
	 **/
	public function handleRequest(HttpRequest $request) {
		return $this->getInner()->handleRequest($request);
	}

	/**
	 * @return ProxyController
	 **/
	public function setRequestType(RequestType $requestType) {
		$this->requestType = $requestType;

		return $this;
	}

	public function isActive($request) {
		return $this->fetchVariable('controller', $request) ? ($this->fetchVariable('controller', $request) == get_class($this->getInner())) : false;
	}

	public function getRequestGetter() {
		Assert::isNotNull($this->requestType);

		if (!$this->requestGetter) {
			$this->requestGetter = self::$requestGetterMap[$this->requestType->getId()];
		}

		return $this->requestGetter;
	}

	private function fetchVariable($name, HttpRequest $request) {
		return $request->{'has' . $this->getRequestGetter() . 'Var'}($name) ? $request->{'get' . $this->getRequestGetter() . 'Var'}($name) : false;
	}
}
