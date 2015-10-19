<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Stringable;
use Hesper\Main\Flow\Model;

/**
 * Class EmptyView
 * @package Hesper\Main\UI\View
 */
class EmptyView implements View, Stringable {

	/**
	 * @return EmptyView
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return EmptyView
	 **/
	public function render(Model $model = null) {
		return $this;
	}

	public function toString() {
		return null;
	}
}
