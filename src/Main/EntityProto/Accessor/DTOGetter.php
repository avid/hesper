<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Primitive\PrimitiveArray;
use Hesper\Core\Form\Primitive\PrimitiveEnumerationList;
use Hesper\Core\Form\Primitive\PrimitiveFormsList;
use Hesper\Core\Form\Primitive\PrimitiveIdentifierList;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedGetter;
use Hesper\Main\Net\Soap\DTOClass;

/**
 * Class DTOGetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class DTOGetter extends PrototypedGetter {

	private $soapDto = true;

	public function __construct(EntityProto $proto, $object) {
		Assert::isInstance($object, DTOClass::class);

		return parent::__construct($proto, $object);
	}

	/**
	 * @return DTOGetter
	 **/
	public function setSoapDto($soapDto) {
		$this->soapDto = ($soapDto === true);

		return $this;
	}

	// FIXME: isSoapDto()
	public function getSoapDto() {
		return $this->soapDto;
	}

	public function get($name) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$method = 'get' . ucfirst($primitive->getName());

		$result = $this->object->$method();

		// TODO: primitives refactoring
		if ($result !== null && $this->soapDto && !is_array($result) && (($primitive instanceof PrimitiveFormsList) || ($primitive instanceof PrimitiveEnumerationList) || ($primitive instanceof PrimitiveIdentifierList) || ($primitive instanceof PrimitiveArray))) {
			$result = [$result];
		}

		return $result;
	}
}
