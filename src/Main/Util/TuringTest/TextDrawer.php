<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class TextDrawer
 * @package Hesper\Main\Util\TuringTest
 */
abstract class TextDrawer extends Drawer
{
	const SPACE_RATIO = 10;

	private $size = null;

	abstract public function draw($text);

	public function __construct($size)
	{
		$this->size = $size;
	}

	/**
	 * @return TextDrawer
	**/
	public function drawCraracter($angle, $x, $y, $character)
	{
		$color = $this->getTuringImage()->getOneCharacterColor();

		imagettftext(
			$this->getTuringImage()->getImageId(),
			$this->size,
			$angle,
			$x,
			$y,
			$color,
			$this->getFont(),
			$character
		);

		return $this;
	}

	protected function getSize()
	{
		return $this->size;
	}

	/**
	 * @return TextDrawer
	**/
	protected function showError()
	{
		$drawer = new ErrorDrawer($this->getTuringImage());
		$drawer->draw();

		return $this;
	}

	protected function getTextWidth($string)
	{
		$textWidth = 0;

		for ($i = 0, $length = strlen($string); $i < $length; ++$i) {
			$character = $string[$i];
			$textWidth += $this->getStringWidth($character) + $this->getSpace();
		}

		return $textWidth;
	}

	protected function getStringWidth($string)
	{
		$bounds = imagettfbbox($this->size, 0, $this->getFont(), $string);

		return $bounds[2] - $bounds[0];
	}

	protected function getStringHeight($string)
	{
		$bounds = imagettfbbox($this->size, 0, $this->getFont(), $string);

		return $bounds[1] - $bounds[7];
	}

	protected function getMaxCharacterHeight()
	{
		return $this->getStringHeight('W'); // bigest character
	}

	protected function getSpace()
	{
		return $this->getSize() / TextDrawer::SPACE_RATIO;
	}

	private function getFont()
	{
		return $this->getTuringImage()->getFont();
	}
}