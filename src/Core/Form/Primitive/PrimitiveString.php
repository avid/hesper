<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Sveta A. Smirnova
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class PrimitiveString
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveString extends FiltrablePrimitive {

	// TODO: consider making a primitive based on main::Net::Mail::MailAddress
	const MAIL_PATTERN = '/^[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/Ds';
	const URL_PATTERN  = '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/is';
	const SHA1_PATTERN = '/^[0-9a-f]{40}$/';
	const MD5_PATTERN  = '/^[0-9a-f]{32}$/';

	protected $pattern = null;

	/**
	 * @return null|string
	 */
	public function getAllowedPattern() {
		return $this->pattern;
	}

	/**
	 * @return PrimitiveString
	 **/
	public function setAllowedPattern($pattern) {
		$this->pattern = $pattern;

		return $this;
	}

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if (!is_scalar($scope[$this->name]) || is_bool($scope[$this->name])) {
			return false;
		}

		$this->value = (string)$scope[$this->name];

		$this->selfFilter();

		if (is_string($this->value) // zero is quite special value here
			&& ($this->value === '0' || !empty($this->value)) && ($length = mb_strlen($this->value)) && !($this->max && $length > $this->max) && !($this->min && $length < $this->min) && (!$this->pattern || preg_match($this->pattern, $this->value))
		) {
			return true;
		} else {
			$this->value = null;
		}

		return false;
	}
}
