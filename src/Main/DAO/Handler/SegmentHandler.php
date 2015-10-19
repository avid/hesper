<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

/**
 * Interface SegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
interface SegmentHandler {

	public function __construct($segmentId);

	/// checks for a key existence in segment
	public function ping($key);

	/// creates key in segment
	public function touch($key);

	/// deletes key from segment
	public function unlink($key);

	/// destroys segment
	public function drop();
}
