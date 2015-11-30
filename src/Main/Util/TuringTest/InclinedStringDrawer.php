<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class InclinedStringDrawer
 * @package Hesper\Main\Util\TuringTest
 */
final class InclinedStringDrawer extends TextDrawer
{
	const MAX_ANGLE	= 70;

	/**
	 * @return InclinedStringDrawer
	**/
	public function draw($string)
	{
		$textWidth = $this->getTextWidth($string);
		$textHeight = $this->getMaxCharacterHeight();

		if ($textWidth < $this->getTuringImage()->getHeight()) {
			$maxAngle = 45;
		} else {
			$maxAngle =
				rad2deg(
					asin(
						($this->getTuringImage()->getHeight() - $textHeight)
						/ $textWidth
					)
				);
		}

		$angle = mt_rand(-$maxAngle / 2, $maxAngle / 2);

		if ($angle > self::MAX_ANGLE)
			$angle = self::MAX_ANGLE;

		if ($angle < -self::MAX_ANGLE)
			$angle = -self::MAX_ANGLE;

		if ($this->getTuringImage()->getWidth() > $textWidth) {
			$x = round(
				(
					($this->getTuringImage()->getWidth() - $textWidth)
					* cos(deg2rad($angle))
				)
				/ 2
			);

			$y = round(
				(
					($this->getTuringImage()->getHeight() + $textWidth)
					* sin(deg2rad($angle))
				)
				/ 2
				+ ($textHeight / 2)
			);

			for ($i = 0, $length = strlen($string); $i < $length; ++$i) {
				$character = $string[$i];

				$this->drawCraracter($angle, $x, $y, $character);

				$charWidth =
					$this->getStringWidth($character)
					+ $this->getSpace();

				$y -= $charWidth * sin(deg2rad($angle));
				$x += $charWidth * cos(deg2rad($angle));
			}
		} else
			return $this->showError();

		return $this;
	}
}