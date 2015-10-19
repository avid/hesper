<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

use Hesper\Main\Flow\Model;

/**
 * Interface View
 * @package Hesper\Main\UI\View
 */
interface View {

	const ERROR_VIEW = 'error';

	/**
	 * @param $model null or Model
	 **/
	public function render(Model $model = null);
}
