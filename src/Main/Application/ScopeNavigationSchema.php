<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Application;

use Hesper\Core\Base\Singleton;

/**
 * Class ScopeNavigationSchema
 * @package Hesper\Main\Application
 */
abstract class ScopeNavigationSchema extends Singleton {

	abstract public function extractPath(&$scope);

	abstract public function getScope($path);
}
