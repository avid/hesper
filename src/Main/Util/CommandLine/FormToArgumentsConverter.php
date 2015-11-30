<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Util\CommandLine;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive\BasePrimitive;
use Hesper\Core\Form\Primitive\PrimitiveNoValue;

/**
 * Class FormToArgumentsConverter
 * @package Hesper\Main\Util\CommandLine
 */
final class FormToArgumentsConverter extends StaticFactory
{
	public static function getShort(Form $form)
	{
		$short = null;

		foreach ($form->getPrimitiveList() as $primitive)
			if (strlen($primitive->getName()) == 1)
				$short .=
					$primitive->getName()
					.self::getValueType($primitive);

		return $short;
	}

	public static function getLong(Form $form)
	{
		$long = array();

		foreach ($form->getPrimitiveList() as $primitive)
			if (strlen($primitive->getName()) > 1)
				$long[] =
					$primitive->getName()
					.self::getValueType($primitive);

		return $long;
	}

	private static function getValueType(BasePrimitive $primitive)
	{
		if ($primitive instanceof PrimitiveNoValue)
			return null;

		if ($primitive->isRequired())
			return ':';
		else
			return '::';
	}
}