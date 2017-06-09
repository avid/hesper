<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Main\Net\Ip\IpAddress;

/**
 * Class PrimitiveIpAddress
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveIpAddress extends BaseObjectPrimitive {

	public function __construct($name) {
		parent::__construct($name);
		$this->className = IpAddress::class;
	}

}
