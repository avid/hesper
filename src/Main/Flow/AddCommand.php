<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

/**
 * @ingroup Flow
 **/
class AddCommand extends MakeCommand {

	/**
	 * @return AddCommand
	 **/
	public static function create() {
		return new self;
	}

	protected function daoMethod() {
		return 'add';
	}
}
