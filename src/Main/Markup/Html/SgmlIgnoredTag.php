<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

/**
 * Class SgmlIgnoredTag
 * @package Hesper\Main\Markup\Html
 */
final class SgmlIgnoredTag extends SgmlTag {

	private $cdata   = null;
	private $endMark = null;

	/**
	 * @return SgmlIgnoredTag
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return SgmlIgnoredTag
	 **/
	public static function comment() {
		return self::create()->setId('!--')->setEndMark('--');
	}

	/**
	 * @return SgmlIgnoredTag
	 **/
	public function setCdata(Cdata $cdata) {
		$this->cdata = $cdata;

		return $this;
	}

	/**
	 * @return Cdata
	 **/
	public function getCdata() {
		return $this->cdata;
	}

	/**
	 * @return SgmlIgnoredTag
	 **/
	public function setEndMark($endMark) {
		$this->endMark = $endMark;

		return $this;
	}

	public function getEndMark() {
		return $this->endMark;
	}

	public function isComment() {
		return $this->id == '!--';
	}

	public function isExternal() {
		return ($this->id && $this->id[0] == '?');
	}
}
