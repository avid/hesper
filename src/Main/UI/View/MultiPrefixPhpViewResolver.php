<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * View resolver for php templates with multiple prefix support
 * Will resolve view to first readable template from supplied prefixes list
 * @package Hesper\Main\UI\View
 */
class MultiPrefixPhpViewResolver implements ViewResolver {

	private $prefixes  = [];
	private $lastAlias = null;

	private $disabled = [];

	private $postfix       = '.tpl.html';
	private $viewClassName = SimplePhpView::class;

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function addFirstPrefix($prefix) {
		array_unshift($this->prefixes, $prefix);

		return $this;
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function addPrefix($prefix, $alias = null) {
		if (!$alias) {
			$alias = $this->getAutoAlias($prefix);
		}

		Assert::isFalse(isset($this->prefixes[$alias]), 'alias already exists');

		$this->prefixes[$alias] = $prefix;

		$this->lastAlias = $alias;

		return $this;
	}

	public function getPrefixes() {
		return $this->prefixes;
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function dropPrefixes() {
		$this->prefixes = [];

		return $this;
	}

	public function isPrefixDisabled($alias) {
		Assert::isIndexExists($this->prefixes, $alias, 'no such alias: ' . $alias);

		return !empty($this->disabled[$alias]);
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function disablePrefix($alias = null, $disabled = true) {
		if (!$alias) {
			$alias = $this->lastAlias;
		}

		Assert::isNotNull($alias, 'nothing to disable');
		Assert::isIndexExists($this->prefixes, $alias, 'no such alias: ' . $alias);

		$this->disabled[$alias] = $disabled;

		return $this;
	}

	public function enablePrefix($alias) {
		return $this->disablePrefix($alias, false);
	}

	public function getPostfix() {
		return $this->postfix;
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function setPostfix($postfix) {
		$this->postfix = $postfix;

		return $this;
	}

	/**
	 * @return SimplePhpView
	 **/
	public function resolveViewName($viewName) {
		Assert::isFalse(($this->prefixes === []), 'specify at least one prefix');

		if ($prefix = $this->findPrefix($viewName)) {
			return $this->makeView($prefix, $viewName);
		}

		if (!$this->findPrefix($viewName, false)) {
			throw new WrongArgumentException('can not resolve view: ' . $viewName);
		}

		return EmptyView::create();
	}

	public function viewExists($viewName) {
		return ($this->findPrefix($viewName) !== null);
	}

	/**
	 * @return MultiPrefixPhpViewResolver
	 **/
	public function setViewClassName($viewClassName) {
		$this->viewClassName = $viewClassName;

		return $this;
	}

	public function getViewClassName() {
		return $this->viewClassName;
	}

	protected function findPrefix($viewName, $checkDisabled = true) {
		foreach ($this->prefixes as $alias => $prefix) {
			if ($checkDisabled && isset($this->disabled[$alias]) && $this->disabled[$alias]) {
				continue;
			}

			if (file_exists($prefix . $viewName . $this->postfix)) {
				return $prefix;
			}
		}

		return null;
	}

	/**
	 * @return View
	 **/
	protected function makeView($prefix, $viewName) {
		return new $this->viewClassName($prefix . $viewName . $this->postfix, $this);
	}

	private function getAutoAlias($prefix) {
		return md5($prefix);
	}
}
