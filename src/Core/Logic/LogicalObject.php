<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\DialectString;

/**
 * Support interface for Form's logic rules.
 * @package Hesper\Core\OSQL
 */
interface LogicalObject extends DialectString {

	public function toBoolean(Form $form);

}
