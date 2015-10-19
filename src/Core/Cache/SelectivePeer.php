<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

/**
 * Class SelectivePeer
 * @package Hesper\Core\Cache
 */
abstract class SelectivePeer extends CachePeer {

	const MARGINAL_VALUE = 'i_am_declassed_element'; // Yanka R.I.P.

	protected $className = null;

	/**
	 * @return SelectivePeer
	 **/
	public function mark($className) {
		$this->className = $className;

		return $this;
	}

	protected function getClassName() {
		if (!$this->className) {
			$class = self::MARGINAL_VALUE;
		} else {
			$class = $this->className;
		}

		$this->className = null; // eat it after use

		return $class;
	}
}
