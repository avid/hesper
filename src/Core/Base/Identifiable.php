<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */

namespace Hesper\Core\Base;

/**
 * Essential interface for DAO-related operations.
 * @package Hesper\Core\Base
 * @see     IdentifiableObject
 */
interface Identifiable {

	public function getId();

	public function setId($id);
}
