<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\NamedObject;

/**
 * Class NamedTree
 * @see     IdentifiableTree
 * @package Hesper\Main\Base
 */
abstract class NamedTree extends NamedObject {

	private $parent = null;

	/**
	 * @return NamedTree
	 **/
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return NamedTree
	 **/
	public function setParent(NamedTree $parent) {
		Assert::brothers($this, $parent);

		$this->parent = $parent;

		return $this;
	}

	/**
	 * @return NamedTree
	 **/
	public function dropParent() {
		$this->parent = null;

		return $this;
	}

	/**
	 * @return NamedTree
	 **/
	public function getRoot() {
		$current = $this;
		$next = $this;

		while ($next) {
			$current = $next;
			$next = $next->getParent();
		}

		return $current;
	}

	public function toString($delimiter = ' :: ') {
		$name = [$this->getName()];

		$parent = $this;

		while ($parent = $parent->getParent()) {
			$name[] = $parent->getName();
		}

		$name = array_reverse($name);

		return implode($delimiter, $name);
	}
}
