<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Net;

/**
 * URL is either absolute URI with authority part or relative one without authority part.
 * @package Hesper\Main\Net
 */
class Url extends GenericUri {

	protected static $knownSubSchemes = ['http' => 'HttpUrl', 'https' => 'HttpUrl', 'ftp' => 'Url', 'nntp' => 'Url', 'telnet' => 'Url', 'gopher' => 'Url', 'wais' => 'Url', 'file' => 'Url', 'prospero' => 'Url'];

	/**
	 * @return Url
	 **/
	public static function create() {
		return new self;
	}

	public static function getKnownSubSchemes() {
		return static::$knownSubSchemes;
	}

	public function isValid() {
		if (!parent::isValid()) {
			return false;
		}

		return ($this->isAbsolute() && $this->getAuthority() !== null) || ($this->isRelative() && $this->getAuthority() === null);
	}

	/**
	 * If scheme is present but authority is empty, authority part is
	 * taken from fisrt non-empty segment, i.e: http:////anything/...
	 * becomes http://anything/...
	 **/
	public function fixAuthorityFromPath() {
		if ($this->scheme && !$this->getAuthority()) {
			$segments = explode('/', $this->path);

			while ($segments && empty($segments[0])) {
				array_shift($segments);
			}

			if ($segments) {
				$this->setAuthority(array_shift($segments));
				$this->setPath('/' . implode('/', $segments));
			}
		}

		return $this;
	}

	/**
	 * see: rfc3986, sec. 4.2, paragraph 4; rfc 2396, sec 3.1
	 **/
	public function fixMistakenPath() {
		if ($this->scheme || $this->getAuthority()) {
			return $this;
		}

		$urlSubSchemes = Url::create()->getKnownSubSchemes();

		$matches = [];

		if (!preg_match('/^([a-z][a-z0-9.+-]*):(.*)/i', $this->path, $matches) || !isset($urlSubSchemes[strtolower($matches[1])])) {
			// localhost:80 not a scheme+authority
			return $this;
		}

		// but http:anything:80/... and http:/anything:80/.. becomes
		// http://anything:80/...

		$this->setScheme($matches[1]);
		$this->setPath($matches[2]);

		$this->fixAuthorityFromPath();

		return $this;
	}

	public function toSmallString() {
		$result = null;

		$authority = $this->getAuthority();

		if ($authority !== null) {
			$result .= $authority;
		}

		$result .= $this->path;

		if ($this->query !== null) {
			$result .= '?' . $this->query;
		}

		if ($this->fragment !== null) {
			$result .= '#' . $this->fragment;
		}

		return $result;
	}

	public function normalize() {
		parent::normalize();

		if ($this->getPort() === '') {
			$this->setPort(null);
		}

		return $this;
	}
}
