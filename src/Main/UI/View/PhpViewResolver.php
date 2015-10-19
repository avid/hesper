<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

/**
 * Class PhpViewResolver
 * @package Hesper\Main\UI\View
 */
class PhpViewResolver implements ViewResolver {

	private $prefix  = null;
	private $postfix = null;

	public function __construct($prefix = null, $postfix = null) {
		$this->prefix = $prefix;
		$this->postfix = $postfix;
	}

	/**
	 * @return PhpViewResolver
	 **/
	public static function create($prefix = null, $postfix = null) {
		return new self($prefix, $postfix);
	}

	/**
	 * @return SimplePhpView
	 **/
	public function resolveViewName($viewName) {
		return new SimplePhpView($this->prefix . $viewName . $this->postfix, $this);
	}

	public function viewExists($viewName) {
		return is_readable($this->prefix . $viewName . $this->postfix);
	}

	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * @return PhpViewResolver
	 **/
	public function setPrefix($prefix) {
		$this->prefix = $prefix;

		return $this;
	}

	public function getPostfix() {
		return $this->postfix;
	}

	/**
	 * @return PhpViewResolver
	 **/
	public function setPostfix($postfix) {
		$this->postfix = $postfix;

		return $this;
	}
}
