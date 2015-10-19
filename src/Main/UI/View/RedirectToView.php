<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Assert;

/**
 * Class RedirectToView
 * @package Hesper\Main\UI\View
 */
final class RedirectToView extends RedirectView {

	private $prefix = null;
	private $suffix = null;

	/**
	 * @return RedirectToView
	 **/
	public static function create($controllerName) {
		return new self($controllerName);
	}

	public function __construct($controllerName) {
		Assert::classExists($controllerName);

		$this->url = $controllerName;
	}

	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * @return RedirectToView
	 **/
	public function setPrefix($prefix) {
		$this->prefix = $prefix;

		return $this;
	}

	public function getSuffix() {
		return $this->suffix;
	}

	/**
	 * @return RedirectToView
	 **/
	public function setSuffix($suffix) {
		$this->suffix = $suffix;

		return $this;
	}

	public function getName() {
		return $this->url;
	}

	/**
	 * @return RedirectToView
	 **/
	public function setName($name) {
		$this->url = $name;

		return $this;
	}

	public function getUrl() {
		return $this->prefix . $this->url . $this->suffix;
	}
}
