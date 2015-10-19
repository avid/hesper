<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

/**
 * Class SumProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class SumProjection extends AggregateProjection {

	public function getFunctionName() {
		return 'sum';
	}
}
