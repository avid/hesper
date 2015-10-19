<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

/**
 * Class OnceBuilder
 * @package Hesper\Meta\Builder
 */
abstract class OnceBuilder extends BaseBuilder {

	protected static function getHead() {
		$head = self::startCap();

		$head .= ' *   This file will never be generated again -' . ' feel free to edit.            *';

		return $head . "\n" . self::endCap();
	}
}
