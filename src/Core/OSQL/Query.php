<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Stringable;

/**
 * Interface Query
 * @package Hesper\Core\OSQL
 */
interface Query extends DialectString, Identifiable, Stringable {}
