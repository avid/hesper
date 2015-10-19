<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\Router;

use Hesper\Main\Flow\HttpRequest;

/**
 * Class RouterTransparentRule
 * @package Hesper\Main\Util\Router
 */
final class RouterTransparentRule extends RouterBaseRule {

	protected $urlVariable    = ':';
	protected $urlDelimiter   = '/';
	protected $regexDelimiter = '#';

	protected $defaultRegex   = null;
	protected $route          = null;
	protected $routeProcessed = false;
	protected $variables      = [];
	protected $parts          = [];
	protected $requirements   = [];
	protected $values         = [];
	protected $wildcardData   = [];

	protected $staticCount = 0;

	/**
	 * @return RouterTransparentRule
	 **/
	public static function create($route) {
		return new self($route);
	}

	public function __construct($route) {
		$this->route = trim($route, $this->urlDelimiter);
	}

	/**
	 * @return RouterTransparentRule
	 **/
	public function setRequirements(array $reqirements) {
		$this->requirements = $reqirements;

		return $this;
	}

	public function getRequirements() {
		return $this->requirements;
	}

	public function match(HttpRequest $request) {
		$this->processRoute();

		$path = $this->processPath($request)->toString();

		$pathStaticCount = 0;
		$values = [];

		$path = trim($path, $this->urlDelimiter);

		if ($path !== '') {
			$path = explode($this->urlDelimiter, $path);

			foreach ($path as $pos => $pathPart) {
				if (!array_key_exists($pos, $this->parts)) {
					return [];
				}

				if ($this->parts[$pos] === '*') {
					$count = count($path);

					for ($i = $pos; $i < $count; $i += 2) {
						$var = urldecode($path[$i]);

						if (!isset($this->wildcardData[$var]) && !isset($this->defaults[$var]) && !isset($values[$var])) {
							$this->wildcardData[$var] = (isset($path[$i + 1])) ? urldecode($path[$i + 1]) : null;
						}
					}

					break;
				}

				$name = isset($this->variables[$pos]) ? $this->variables[$pos] : null;

				$pathPart = urldecode($pathPart);

				if (($name === null) && ($this->parts[$pos] != $pathPart)) {
					return [];
				}

				if ($this->parts[$pos] !== null && !preg_match($this->regexDelimiter . '^' . $this->parts[$pos] . '$' . $this->regexDelimiter . 'iu', $pathPart)) {
					return [];
				}

				if ($name !== null) {
					$values[$name] = $pathPart;
				} else {
					++$pathStaticCount;
				}
			}
		}

		if ($this->staticCount != $pathStaticCount) {
			return [];
		}

		$return = $values + $this->wildcardData + $this->defaults;

		foreach ($this->variables as $var) {
			if (!array_key_exists($var, $return)) {
				return [];
			}
		}

		$this->values = $values;

		return $return;
	}

	/**
	 * Assembles user submitted parameters forming a URL path
	 * defined by this route.
	 *
	 * @param array   $data  An array of variable and value pairs used as parameters.
	 * @param boolean $reset Whether or not to set route defaults with those provided in $data.
	 * @param bool    $encode Whether or not to use urlencode onto values.
	 *
	 * @return string Route path with user submitted parameters.
	 * @throws RouterException
	 */
	public function assembly(array $data = [], $reset = false, $encode = false) {
		$this->processRoute();

		$url = [];
		$flag = false;

		foreach ($this->parts as $key => $part) {
			$name = isset($this->variables[$key]) ? $this->variables[$key] : null;

			$useDefault = false;

			if ($name && array_key_exists($name, $data) && ($data[$name] === null)) {
				$useDefault = true;
			}

			if ($name) {
				if (isset($data[$name]) && !$useDefault) {
					$url[$key] = $data[$name];
					unset($data[$name]);
				} elseif (!$reset && !$useDefault && isset($this->values[$name])) {
					$url[$key] = $this->values[$name];
				} elseif (!$reset && !$useDefault && isset($this->wildcardData[$name])) {
					$url[$key] = $this->wildcardData[$name];
				} elseif (isset($this->defaults[$name])) {
					$url[$key] = $this->defaults[$name];
				} else {
					// FIXME: bogus message
					throw new RouterException("{$name} is not specified");
				}
			} elseif ($part !== '*') {
				$url[$key] = $part;
			} else {
				if (!$reset) {
					$data += $this->wildcardData;
				}

				foreach ($data as $var => $value) {
					if ($value !== null) {
						if (isset($this->defaults[$var]) && ($this->defaults[$var] === $value)) {
							continue;
						}

						$url[$key++] = $var;
						$url[$key++] = $value;
						$flag = true;
					}
				}
			}
		}

		$return = null;

		foreach (array_reverse($url, true) as $key => $value) {
			if ($flag || !isset($this->variables[$key]) || ($value !== $this->getDefault($this->variables[$key]))) {
				if ($encode) {
					$value = urlencode($value);
				}

				$return = $this->urlDelimiter . $value . $return;

				$flag = true;
			}
		}

		// FIXME: rtrim, probably?
		return trim($return, $this->urlDelimiter);
	}

	/**
	 * @return RouterTransparentRule
	 **/
	protected function processRoute() {
		if ($this->routeProcessed) {
			return $this;
		}

		if ($this->route !== '') {
			foreach (explode($this->urlDelimiter, $this->route) as $pos => $part) {
				if (substr($part, 0, 1) == $this->urlVariable) {
					$name = substr($part, 1);

					$this->parts[$pos] = (isset($this->requirements[$name]) ? $this->requirements[$name] : $this->defaultRegex);

					$this->variables[$pos] = $name;
				} else {
					$this->parts[$pos] = $part;

					if ($part !== '*') {
						$this->staticCount++;
					}
				}
			}
		}

		$this->routeProcessed = true;

		return $this;
	}
}
