<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class LinearStringDrawer
 * @package Hesper\Main\Util\TuringTest
 */
final class LinearStringDrawer extends TextDrawer
{
	/**
	 * @return LinearStringDrawer
	**/
	public function draw($string)
	{
		$maxHeight = $this->getMaxCharacterHeight();
		$y = round($this->getTuringImage()->getHeight() / 2 + $maxHeight / 2);

		$textWidth = $this->getTextWidth($string);

		if ($this->getTuringImage()->getWidth() <= $textWidth)
			return $this->showError();

		$x = round(($this->getTuringImage()->getWidth() - $textWidth) / 2);
		$angle = 0;

		for ($i = 0, $length = strlen($string); $i < $length; ++$i) {
			$character = $string[$i];
			$this->drawCraracter($angle, $x, $y, $character);
			$x += $this->getStringWidth($character) + $this->getSpace();
		}

		return $this;
	}
}