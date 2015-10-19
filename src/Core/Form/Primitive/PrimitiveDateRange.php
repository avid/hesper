<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Igor V. Gulyaev
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\DateRange;
use Hesper\Main\Util\ClassUtils;

/**
 * Class PrimitiveDateRange
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveDateRange extends FiltrablePrimitive {

	private $className = null;

	/**
	 * @return PrimitiveDateRange
	 **/
	public static function create($name) {
		return new self($name);
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveDateRange
	 **/
	public function of($class) {
		Assert::isTrue(ClassUtils::isInstanceOf($class, $this->getObjectName()));

		$this->className = $class;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveDateRange
	 **/
	public function setDefault(/* DateRange */
		$object) {
		$this->checkType($object);

		$this->default = $object;

		return $this;
	}

	public function importValue($value) {
		try {
			if ($value) {
				$this->checkType($value);

				if ($this->checkRanges($value)) {
					$this->value = $value;

					return true;
				} else {
					return false;
				}
			} else {
				return parent::importValue(null);
			}
		} catch (WrongArgumentException $e) {
			return false;
		}
	}

	public function import($scope) {
		if (parent::import($scope)) {
			$listName = $this->getObjectName() . 'List';
			try {
				$range = $this->makeRange($scope[$this->name]);
			} catch (WrongArgumentException $e) {
				return false;
			}

			if ($this->checkRanges($range)) {
				if ($this->className && ($this->className != $this->getObjectName())) {
					$newRange = new $this->className;

					if ($start = $range->getStart()) {
						$newRange->setStart($start);
					}

					if ($end = $range->getEnd()) {
						$newRange->setEnd($end);
					}

					$this->value = $newRange;

					return true;
				}

				$this->value = $range;

				return true;
			}
		}

		return false;
	}

	protected function getObjectName() {
		return 'DateRange';
	}

	protected function checkRanges(DateRange $range) {
		return !($this->min && ($this->min->toStamp() < $range->getStartStamp())) && !($this->max && ($this->max->toStamp() > $range->getEndStamp()));
	}

	protected function makeRange($string) {
		return DateRangeList::makeRange($string);
	}

	/* void */
	private function checkType($object) {
		if ($this->className) {
			Assert::isTrue(ClassUtils::isInstanceOf($object, $this->className));
		} else {
			Assert::isTrue(ClassUtils::isInstanceOf($object, $this->getObjectName()));
		}
	}
}
