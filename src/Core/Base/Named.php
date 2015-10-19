<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

/**
 * Interface Named
 * @package Hesper\Core\Base
 * @see     NamedObject
 */
interface Named extends Identifiable {

	public function getName();

	public function setName($name);
}
