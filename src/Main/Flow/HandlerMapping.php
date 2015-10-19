<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

/**
 * @ingroup Flow
 **/
interface HandlerMapping {

	/**
	 * @return Controller
	 **/
	public function getController(HttpRequest $request);
}
