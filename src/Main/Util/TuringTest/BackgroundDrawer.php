<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class BackgroundDrawer
 * @package Hesper\Main\Util\TuringTest
 */
abstract class BackgroundDrawer extends Drawer
{
	abstract public function draw();

	/**
	 * @return Color
	**/
	public function makeColor()
	{
		$color = $this->getTuringImage()->getTextColors()->getRandomTextColor();

		$invertColor = clone $color;
		$invertColor->invertColor();

		return $invertColor;
	}
}