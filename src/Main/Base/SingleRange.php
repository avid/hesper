<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladimir A. Altuchov
 */
namespace Hesper\Main\Base;

/**
 * Interface SingleRange
 * @package Hesper\Main\Base
 */
interface SingleRange {

	public function getStart();

	public function getEnd();

	public function contains($probe);
}
