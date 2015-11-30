<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util\TuringTest;

/**
 * Class Drawer
 * @package Hesper\Main\Util\TuringTest
 */
abstract class Drawer
{
	private	$turingImage	= null;

	/**
	 * @return Drawer
	**/
	public function setTuringImage(TuringImage $turingImage)
	{
		$this->turingImage = $turingImage;

		return $this;
	}

	/**
	 * @return TuringImage
	**/
	public function getTuringImage()
	{
		return $this->turingImage;
	}
}