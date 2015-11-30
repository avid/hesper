<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class WavesBackgroundDrawer
 * @package Hesper\Main\Util\TuringTest
 */
final class WavesBackgroundDrawer extends BackgroundDrawer
{
	const MIN_WAVE_DISTANCE	= 8;
	const MAX_WAVE_DISTANCE	= 20;
	const MAX_WAVE_OFFSET	= 5;

	/**
	 * @return WavesBackgroundDrawer
	**/
	public function draw()
	{
		$y = mt_rand(-self::MAX_WAVE_OFFSET, self::MAX_WAVE_OFFSET);

		while ($y < $this->getTuringImage()->getHeight()) {
			$this->drawWave($y);

			$y += mt_rand(self::MIN_WAVE_DISTANCE, self::MAX_WAVE_DISTANCE);
		}

		return $this;
	}

	/* void */ private function drawWave($y)
	{
		$radius = 5;
		$frequency = 30;

		$imageId = $this->getTuringImage()->getImageId();

		for (
			$x = 0, $width = $this->getTuringImage()->getWidth();
			$x < $width;
			++$x
		) {
			$color = $this->makeColor();
			$colorId = $this->getTuringImage()->getColorIdentifier($color);

			$angle = $x % $frequency;
			$angle = 2 * M_PI * $angle / $frequency;

			$dy = $radius * sin($angle);

			imagesetpixel(
				$imageId,
				$x,
				$y + $dy,
				$colorId
			);
		}
	}
}