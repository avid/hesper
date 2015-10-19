<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Interface ListedPrimitive
 * @package Hesper\Core\Form\Primitive
 */
interface ListedPrimitive {

	/// @return plain array of possible primitive choices
	public function getList();

	public function setList($list);

	public function getChoiceValue();

	public function getActualChoiceValue();
}
