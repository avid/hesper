<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\Router;

use Hesper\Core\Base\StaticFactory;

final class RouterUrlHelper extends StaticFactory {

	/**
	 * @return string
	 **/
	public static function url(array $urlOptions = [], $name, $reset = false, $encode = true) {
		return RouterRewrite::me()->assembly($urlOptions, $name, $reset, $encode);
	}
}
