<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Artem Naumenko
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Base\Listable;

/**
 * @param string $key
 * @return Listable
 */

interface ListGenerator {

	public function fetchList($key);

}
