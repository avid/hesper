<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Enum;

/**
 * Class ForeignChangeAction
 * @package Hesper\Core\OSQL
 */
final class ForeignChangeAction extends Enum {

	const NO_ACTION   = 0x01;
	const RESTRICT    = 0x02;
	const CASCADE     = 0x03;
	const SET_NULL    = 0x04;
	const SET_DEFAULT = 0x05;

	protected static $names = [
		self::NO_ACTION => 'NO ACTION', // default one
		self::RESTRICT => 'RESTRICT',
		self::CASCADE => 'CASCADE',
		self::SET_NULL => 'SET NULL',
		self::SET_DEFAULT => 'SET DEFAULT'
	];

	/**
	 * @return ForeignChangeAction
	 **/
	public static function noAction() {
		return new self(self::NO_ACTION);
	}

	/**
	 * @return ForeignChangeAction
	 **/
	public static function restrict() {
		return new self(self::RESTRICT);
	}

	/**
	 * @return ForeignChangeAction
	 **/
	public static function cascade() {
		return new self(self::CASCADE);
	}

	/**
	 * @return ForeignChangeAction
	 **/
	public static function setNull() {
		return new self(self::SET_NULL);
	}

	/**
	 * @return ForeignChangeAction
	 **/
	public static function setDefault() {
		return new self(self::SET_DEFAULT);
	}
}
