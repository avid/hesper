<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\SPL;

/**
 * Interface SimplifiedArrayAccess
 * @package Hesper\Main\SPL
 */
interface SimplifiedArrayAccess {

	public function clean();

	public function isEmpty();

	public function getList();

	public function set($name, $var);

	public function get($name);

	public function has($name);

	public function drop($name);
}
