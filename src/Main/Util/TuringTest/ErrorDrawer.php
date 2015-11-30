<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class ErrorDrawer
 * @package Hesper\Main\Util\TuringTest
 */
final class ErrorDrawer
{
	const FONT_SIZE	= 4;

	private static $drawError	= true;

	public static function setDrawError($drawError = false)
	{
		self::$drawError = $drawError;
	}

	public static function isDrawError()
	{
		return self::$drawError;
	}

	public function __construct($turingImage)
	{
		$this->turingImage = $turingImage;
	}

	/**
	 * @return ErrorDrawer
	**/
	public function draw($string = 'ERROR!')
	{
		if (!ErrorDrawer::isDrawError())
			return $this;

		$y = round(
			$this->turingImage->getHeight() / 2
			- imagefontheight(ErrorDrawer::FONT_SIZE) / 2
		);

		$textWidth = imagefontwidth(ErrorDrawer::FONT_SIZE) * strlen($string);

		if ($this->turingImage->getWidth() > $textWidth)
			$x = round(($this->turingImage->getWidth() - $textWidth) / 2);
		else
			$x = 0;

		$color = $this->turingImage->getOneCharacterColor();

		imagestring(
			$this->turingImage->getImageId(),
			ErrorDrawer::FONT_SIZE,
			$x,
			$y,
			$string,
			$color
		);

		return $this;
	}
}