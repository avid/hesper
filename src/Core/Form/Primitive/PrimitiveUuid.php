<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\Form\Primitive;

class PrimitiveUuid extends PrimitiveString {

	const UUID_PATTERN = '/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i';

	/**
	 * @param string $name
	 * @return PrimitiveUuid
	 */
	public static function create($name)
	{
		return new self($name);
	}

	public function __construct($name)
	{
		parent::__construct($name);
		$this->setAllowedPattern(self::UUID_PATTERN);
	}

}
