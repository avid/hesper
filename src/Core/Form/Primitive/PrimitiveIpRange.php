<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Main\Net\Ip\IpRange;

/**
 * Class PrimitiveIpRange
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveIpRange extends BaseObjectPrimitive {

	public function __construct($name) {
		parent::__construct($name);
		$this->className = IpRange::class;
	}

}
