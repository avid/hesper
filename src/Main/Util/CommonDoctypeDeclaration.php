<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util;

/**
 * Parses common doctype FPI (Formal Public Identifier) in form:
 * "-//<Org>//DTD <Type> [<Subtype>] [<Version>] [<Variant>]//<Language>"
 * Examples:
 * -//W3C//DTD HTML//EN
 * -//W3C//DTD XHTML Basic 1.0//EN
 * -//W3C//DTD HTML 4.01 Transitional//EN
 * @ingroup Utils
 **/
final class CommonDoctypeDeclaration extends DoctypeDeclaration {

	private $organization = null;

	private $type     = null;
	private $subtype  = null;
	private $version  = null;
	private $variant  = null;
	private $language = null;

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setOrganization($organization) {
		$this->organization = $organization;

		return $this;
	}

	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setType($type) {
		$this->type = $type;

		return $this;
	}

	public function getType() {
		return $this->type;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setSubtype($subtype) {
		$this->subtype = $subtype;

		return $this;
	}

	public function getSubtype() {
		return $this->subtype;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setVersion($version) {
		$this->version = $version;

		return $this;
	}

	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setVariant($variant) {
		$this->variant = $variant;

		return $this;
	}

	public function getVariant() {
		return $this->variant;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setLanguage($language) {
		$this->language = $language;

		return $this;
	}

	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @return CommonDoctypeDeclaration
	 **/
	public function setFpi($fpi) {
		parent::setFpi($fpi);

		$matches = [];

		preg_match('~^-//([a-z0-9]+)//DTD ([a-z]+)' . ' ?([a-z]+)? ?(\d+\.\d+)?' . ' ?([a-z]+)?//([a-z]+)$~i', $fpi, $matches);

		$this->organization = !empty($matches[1]) ? $matches[1] : null;
		$this->type = !empty($matches[2]) ? $matches[2] : null;

		$this->subtype = !empty($matches[3]) ? $matches[3] : null;
		$this->version = !empty($matches[4]) ? $matches[4] : null;
		$this->variant = !empty($matches[5]) ? $matches[5] : null;
		$this->language = !empty($matches[6]) ? $matches[6] : null;

		return $this;
	}

	public function getFpi() {
		if (!$this->organization) {
			return null;
		}

		return '-//' . $this->organization . '//DTD ' . $this->type . ($this->subtype ? ' ' . $this->subtype : null) . ($this->version ? ' ' . $this->version : null) . ($this->variant ? ' ' . $this->variant : null) . '//' . $this->language;
	}
}
