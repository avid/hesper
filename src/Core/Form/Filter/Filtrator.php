<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Filter;

/**
 * Interface for primitive's filters.
 * @see     FiltrablePrimitive::getDisplayValue()
 * @package Hesper\Core\Form
 */
interface Filtrator {

	public function apply($value);
}
