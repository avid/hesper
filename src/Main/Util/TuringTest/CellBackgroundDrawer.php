<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class CellBackgroundDrawer
 * @package Hesper\Main\Util\TuringTest
 */
final class CellBackgroundDrawer extends BackgroundDrawer
{
	private $step = null;

	public function __construct($step)
	{
		$this->step = $step;
	}

	/**
	 * @return CellBackgroundDrawer
	**/
	public function draw()
	{
		$x = mt_rand(-$this->step, $this->step);
		$width = $this->getTuringImage()->getWidth();

		while ($x < $width) {
			$color = $this->makeColor();
			$colorId = $this->getTuringImage()->getColorIdentifier($color);

			imageline(
				$this->getTuringImage()->getImageId(),
				$x,
				0,
				$x,
				$this->getTuringImage()->getHeight(),
				$colorId
			);

			$x += $this->step;
		}

		$y = mt_rand(-$this->step, $this->step);
		$height = $this->getTuringImage()->getHeight();

		while ($y < $height) {
			$color = $this->makeColor();
			$colorId = $this->getTuringImage()->getColorIdentifier($color);

			imageline(
				$this->getTuringImage()->getImageId(),
				0,
				$y,
				$this->getTuringImage()->getWidth(),
				$y,
				$colorId
			);

			$y += $this->step;
		}

		return $this;
	}
}