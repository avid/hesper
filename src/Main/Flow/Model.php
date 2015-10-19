<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Main\SPL\SimplifiedArrayAccess;

/**
 * Class Model
 * @package Hesper\Main\Flow
 */
class Model implements SimplifiedArrayAccess {

	private $vars = [];

	/**
	 * @return Model
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return Model
	 **/
	public function clean() {
		$this->vars = [];

		return $this;
	}

	public function isEmpty() {
		return ($this->vars === []);
	}

	public function getList() {
		return $this->vars;
	}

	/**
	 * @return Model
	 **/
	public function set($name, $var) {
		$this->vars[$name] = $var;

		return $this;
	}

	public function get($name) {
		if (!$this->has($name)) {
			throw new MissingElementException('Unknown var "' . $name . '"');
		}

		return $this->vars[$name];
	}

	public function has($name) {
		return isset($this->vars[$name]);
	}

	/**
	 * @return Model
	 **/
	public function drop($name) {
		unset($this->vars[$name]);

		return $this;
	}

	/**
	 * @return Model
	 **/
	public function merge(Model $model, $overwrite = false) {
		if (!$model->isEmpty()) {

			$vars = $model->getList();
			foreach ($vars as $name => $value) {
				if (!$overwrite && $this->has($name)) {
					continue;
				}
				$this->set($name, $value);
			}

		}

		return $this;
	}
}
