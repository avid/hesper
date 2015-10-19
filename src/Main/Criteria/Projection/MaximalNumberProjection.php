<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

/**
 * Class MaximalNumberProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class MaximalNumberProjection extends AggregateProjection {

	public function getFunctionName() {
		return 'max';
	}
}
