<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

/**
 * Class SgmlTag
 * @package Hesper\Main\Markup\Html
 */
abstract class SgmlTag extends SgmlToken {

	protected $id = null;

	/**
	 * @return SgmlTag
	 **/
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	public function getId() {
		return $this->id;
	}
}
