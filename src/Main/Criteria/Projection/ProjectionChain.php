<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Base\Assert;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\Criteria\Criteria;

/**
 * Class ProjectionChain
 * @package Hesper\Main\Criteria\Projection
 */
final class ProjectionChain implements ObjectProjection {

	private $list = [];

	public function getList() {
		return $this->list;
	}

	/**
	 * @return ProjectionChain
	 **/
	public function add(ObjectProjection $projection, $name = null) {
		if ($name) {
			Assert::isFalse(isset($this->list[$name]));

			$this->list[$name] = $projection;
		} else {
			$this->list[] = $projection;
		}

		return $this;
	}

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		foreach ($this->list as $projection) {
			$projection->process($criteria, $query);
		}

		return $query;
	}

	public function isEmpty() {
		return count($this->list) == 0;
	}

	/**
	 * @return ProjectionChain
	 **/
	public function dropByType(/* array */
		$dropTypes) {
		$newList = [];

		if (!is_array($dropTypes)) {
			$dropTypes = [$dropTypes];
		}

		foreach ($this->list as $name => &$projection) {
			$class = get_class($projection);

			if (!in_array($class, $dropTypes)) {
				$newList[$name] = $projection;
			}
		}

		// swap
		$this->list = $newList;

		return $this;
	}
}
