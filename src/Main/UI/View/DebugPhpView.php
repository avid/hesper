<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin, Ivan Y. Khvostishkov
 */
namespace Hesper\Main\UI\View;

/**
 * Class DebugPhpView
 * @package Hesper\Main\UI\View
 */
final class DebugPhpView extends SimplePhpView {

	/**
	 * @return DebugPhpView
	 **/
	public function preRender() {
		$trace = debug_backtrace();

		echo "<div style='margin:2px;padding:2px;border:1px solid #f00;'>";

		if (isset($trace[2])) {
			echo $trace[2]['file'] . ' (' . $trace[2]['line'] . '): ';
		}

		echo $this->templatePath;

		return $this;
	}

	/**
	 * @return DebugPhpView
	 **/
	protected function postRender() {
		echo "</div>";

		return $this;
	}
}
