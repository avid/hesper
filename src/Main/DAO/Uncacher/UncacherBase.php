<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

/**
 * Interface UncacherBase
 * @package Hesper\Main\DAO\Uncacher
 */
interface UncacherBase {

	/**
	 * @param $uncacher UncacherBase same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher);

	public function uncache();
}
