<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Assert;

/**
 * Class RedirectView
 * @package Hesper\Main\UI\View
 */
class RedirectView extends CleanRedirectView {

	private $falseAsUnset = null;
	private $buildArrays  = null;

	/**
	 * @return RedirectView
	 **/
	public static function create($url) {
		return new self($url);
	}

	public function isFalseAsUnset() {
		return $this->falseAsUnset;
	}

	/**
	 * @return RedirectView
	 **/
	public function setFalseAsUnset($really) {
		Assert::isBoolean($really);

		$this->falseAsUnset = $really;

		return $this;
	}

	public function isBuildArrays() {
		return $this->buildArrays;
	}

	/**
	 * @return RedirectView
	 **/
	public function setBuildArrays($really) {
		Assert::isBoolean($really);

		$this->buildArrays = $really;

		return $this;
	}

	protected function getLocationUrl($model = null) {
		$postfix = null;

		if ($model && $model->getList()) {
			$qs = [];

			foreach ($model->getList() as $key => $val) {
				if ((null === $val) || is_object($val)) {
					continue;
				} elseif (is_array($val)) {
					if ($this->buildArrays) {
						$qs[] = http_build_query([$key => $val], null, '&');
					}

					continue;

				} elseif (is_bool($val)) {
					if ($this->isFalseAsUnset() && (false === $val)) {
						continue;
					}

					$val = (int)$val;
				}

				$qs[] = $key . '=' . urlencode($val);
			}

			if (strpos($this->getUrl(), '?') === false) {
				$first = '?';
			} else {
				$first = '&';
			}

			if ($qs) {
				$postfix = $first . implode('&', $qs);
			}
		}

		return $this->getUrl() . $postfix;
	}
}
