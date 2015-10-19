<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

/**
 * Class AverageNumberProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class AverageNumberProjection extends AggregateProjection {

	public function getFunctionName() {
		return 'avg';
	}
}
