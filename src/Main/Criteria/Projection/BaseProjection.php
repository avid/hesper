<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Base\Aliased;

/**
 * Class BaseProjection
 * @package Hesper\Main\Criteria\Projection
 */
abstract class BaseProjection implements ObjectProjection, Aliased {

	protected $property = null;
	protected $alias    = null;

	public function __construct($propertyName = null, $alias = null) {
		$this->property = $propertyName;
		$this->alias = $alias;
	}

	public function getAlias() {
		return $this->alias;
	}
}
