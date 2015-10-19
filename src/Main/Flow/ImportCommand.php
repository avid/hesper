<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

/**
 * Class ImportCommand
 * @package Hesper\Main\Flow
 */
class ImportCommand extends MakeCommand {

	/**
	 * @return ImportCommand
	 **/
	public static function create() {
		return new self;
	}

	protected function daoMethod() {
		return 'import';
	}
}
