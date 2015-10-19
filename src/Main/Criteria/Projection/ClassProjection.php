<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\Criteria\Criteria;
use Hesper\Main\Util\ClassUtils;

/**
 * Class ClassProjection
 * @package Hesper\Main\Criteria\Projection
 */
class ClassProjection implements ObjectProjection {

	protected $className = null;

	/**
	 * @return ClassProjection
	 **/
	public static function create($class) {
		return new self($class);
	}

	public function __construct($class) {
		Assert::isTrue(ClassUtils::isInstanceOf($class, Prototyped::class));

		if (is_object($class)) {
			$this->className = get_class($class);
		} else {
			$this->className = $class;
		}
	}

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		$dao = call_user_func([$this->className, 'dao']);

		foreach ($dao->getFields() as $field) {
			$this->subProcess($query, DBField::create($field, $dao->getTable()));
		}

		return $query;
	}

	/* void */
	protected function subProcess(JoinCapableQuery $query, DBField $field) {
		$query->get($field);
	}
}
