<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

/**
 * Interface ViewResolver
 * @package Hesper\Main\UI\View
 */
interface ViewResolver {

	/**
	 * @param    $viewName    string
	 * @return    View
	 **/
	public function resolveViewName($viewName);
}
