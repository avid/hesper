<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

use Hesper\Core\Exception\MissingElementException;

/**
 * Class ColorArray
 * @package Hesper\Main\Util\TuringTest
 */
final class ColorArray
{
	private $colors = array();

	/**
	 * @return ColorArray
	**/
	public function add(Color $color)
	{
		$this->colors[] = $color;

		return $this;
	}

	/**
	 * @return ColorArray
	**/
	public function clear()
	{
		unset($this->colors);

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return Color
	**/
	public function getRandomTextColor()
	{
		if ($this->isEmpty())
			throw new MissingElementException();

		return $this->colors[array_rand($this->colors)];
	}

	public function getColors()
	{
		return $this->colors;
	}

	public function isEmpty()
	{
		if (count($this->colors) == 0)
			return true;
		else
			return false;
	}
}