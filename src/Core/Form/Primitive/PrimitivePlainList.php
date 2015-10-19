<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class PrimitivePlainList
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitivePlainList extends PrimitiveList {

	/**
	 * @return PrimitivePlainList
	 **/
	public function setList($list) {
		$this->list = array_combine($list, $list);

		return $this;
	}
}
