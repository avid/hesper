<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Net\Http;

use Hesper\Core\Base\Enum;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class HttpMethod
 * @package Hesper\Main\Net\Http
 */
final class HttpMethod extends Enum {

	const OPTIONS	= 1;
	const GET		= 2;
	const HEAD 		= 3;
	const POST		= 4;
	const PUT		= 5;
	const DELETE	= 6;
	const TRACE		= 7;
	const CONNECT	= 8;
	const PROPFIND	= 9;
	const PROPPATCH	= 10;
	const MKCOL 	= 11;
	const COPY		= 12;
	const MOVE		= 13;
	const LOCK		= 14;
	const UNLOCK	= 15;

	protected static $names = [
		self::OPTIONS 	=> 'OPTIONS',
		self::GET		=> 'GET',
		self::HEAD		=> 'HEAD',
		self::POST		=> 'POST',
		self::PUT		=> 'PUT',
		self::DELETE	=> 'DELETE',
		self::TRACE 	=> 'TRACE',
		self::CONNECT 	=> 'CONNECT',
		self::PROPFIND	=> 'PROPFIND',
		self::PROPPATCH => 'PROPPATCH',
		self::MKCOL 	=> 'MKCOL',
		self::COPY		=> 'COPY',
		self::MOVE		=> 'MOVE',
		self::LOCK		=> 'LOCK',
		self::UNLOCK 	=> 'UNLOCK',
	];

	public static function get() {
		return new self(self::GET);
	}

	public static function post() {
		return new self(self::POST);
	}

	/**
	 * @return HttpMethod
	 */
	public static function any() {
		return self::get();
	}

	public static function createByName($name) {
		$key = array_search($name, self::$names);

		if ($key === false) {
			throw new WrongArgumentException();
		}

		return new self($key);
	}
}