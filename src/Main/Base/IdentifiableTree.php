<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\IdentifiableObject;
use Hesper\Core\Base\Stringable;

/**
 * @see     NamedTree
 * @ingroup Helpers
 **/
abstract class IdentifiableTree extends IdentifiableObject implements Stringable {

	private $parent = null;

	/**
	 * @return IdentifiableTree
	 **/
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return IdentifiableTree
	 **/
	public function setParent(IdentifiableTree $parent) {
		Assert::brothers($this, $parent);

		$this->parent = $parent;

		return $this;
	}

	/**
	 * @return IdentifiableTree
	 **/
	public function dropParent() {
		$this->parent = null;

		return $this;
	}

	/**
	 * @return IdentifiableTree
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

	public function toString($delimiter = ', ') {
		$ids = [$this->getId()];

		$parent = $this;

		while ($parent = $parent->getParent()) {
			$ids[] = $parent->getId();
		}

		$ids = array_reverse($ids);

		return implode($delimiter, $ids);
	}
}
