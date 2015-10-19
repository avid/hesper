<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

/**
 * Filter's template.
 * @package Hesper\Core\Form
 */
abstract class BaseFilter extends Singleton implements Filtrator, Instantiatable {}
