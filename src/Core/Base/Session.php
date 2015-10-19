<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\SessionNotStartedException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Simple static wrapper around session_*() functions.
 * @package Hesper\Core\Base
 */
final class Session extends StaticFactory {

	private static $isStarted = false;

	public static function start() {
		session_start();
		self::$isStarted = true;
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	/* void */
	public static function destroy() {
		if (!self::$isStarted) {
			throw new SessionNotStartedException();
		}

		self::$isStarted = false;

		try {
			session_destroy();
		} catch (BaseException $e) {
			// stfu
		}

		setcookie(session_name(), null, 0, '/');
	}

	public static function flush() {
		return session_unset();
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	/* void */
	public static function assign($var, $val) {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		$_SESSION[$var] = $val;
	}

	/**
	 * @throws WrongArgumentException
	 * @throws SessionNotStartedException
	 **/
	public static function exist(/* ... */) {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		if (!func_num_args()) {
			throw new WrongArgumentException('missing argument(s)');
		}

		foreach (func_get_args() as $arg) {
			if (!isset($_SESSION[$arg])) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	public static function get($var) {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		return isset($_SESSION[$var]) ? $_SESSION[$var] : null;
	}

	public static function &getAll() {
		return $_SESSION;
	}

	/**
	 * @throws WrongArgumentException
	 * @throws SessionNotStartedException
	 **/
	/* void */
	public static function drop(/* ... */) {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		if (!func_num_args()) {
			throw new WrongArgumentException('missing argument(s)');
		}

		foreach (func_get_args() as $arg) {
			unset($_SESSION[$arg]);
		}
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	/* void */
	public static function dropAll() {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		if ($_SESSION) {
			foreach (array_keys($_SESSION) as $key) {
				self::drop($key);
			}
		}
	}

	public static function isStarted() {
		return self::$isStarted;
	}

	/**
	 * assigns to $_SESSION scope variables defined in given array
	 **/
	/* void */
	public static function arrayAssign($scope, $array) {
		Assert::isArray($array);

		foreach ($array as $var) {
			if (isset($scope[$var])) {
				$_SESSION[$var] = $scope[$var];
			}
		}
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	public static function getName() {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		return session_name();
	}

	/**
	 * @throws SessionNotStartedException
	 **/
	public static function getId() {
		if (!self::isStarted()) {
			throw new SessionNotStartedException();
		}

		return session_id();
	}
}
