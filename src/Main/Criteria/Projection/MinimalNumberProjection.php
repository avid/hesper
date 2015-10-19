<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

/**
 * Class MinimalNumberProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class MinimalNumberProjection extends AggregateProjection {

	public function getFunctionName() {
		return 'min';
	}
}
