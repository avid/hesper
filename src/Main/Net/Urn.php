<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Net;

/**
 * URN is an absolute URI without authority part.
 * @ingroup Net
 **/
final class Urn extends GenericUri {

	protected $schemeSpecificPart = null;

	protected static $knownSubSchemes = ['urn' => 'Urn', 'mailto' => 'Urn', 'news' => 'Urn', 'isbn' => 'Urn', 'tel' => 'Urn', 'fax' => 'Urn',];

	/**
	 * @return Urn
	 **/
	public static function create() {
		return new self;
	}

	public static function getKnownSubSchemes() {
		return static::$knownSubSchemes;
	}

	public function isValid() {
		if ($this->scheme === null || $this->getAuthority() !== null) {
			return false;
		}

		return parent::isValid();
	}
}
