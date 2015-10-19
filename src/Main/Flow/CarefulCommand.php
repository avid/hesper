<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

/**
 * Interface CarefulCommand
 * @package Hesper\Main\Flow
 */
interface CarefulCommand extends EditorCommand {

	public function commit();

	public function rollback();
}
