<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

/**
 * Interface RandomSource
 * @package Hesper\Main\Math
 */
interface RandomSource {

	public function getBytes($numberOfBytes);
}
