<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enum;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Generic SQL data types.
 * @package Hesper\Core\OSQL
 */
final class DataType extends Enum implements DialectString {

	const SMALLINT = 0x001001;
	const INTEGER  = 0x001002;
	const BIGINT   = 0x001003;
	const NUMERIC  = 0x001704;

	const REAL   = 0x001105;
	const DOUBLE = 0x001106;

	const BOOLEAN = 0x000007;

	const CHAR    = 0x000108;
	const VARCHAR = 0x000109;
	const TEXT    = 0x00000A;

	const HSTORE    = 0x00000B;
	const UUID      = 0x00000C;

	const JSON      		= 0x000033;
	const JSONB      		= 0x000034;

	const DATE        = 0x00000D;
	const TIME        = 0x000A0C;
	const TIMESTAMP   = 0x000A0D;
	const TIMESTAMPTZ = 0x000A0E;

	const INTERVAL = 0x00000F;

	const BINARY = 0x00000E;

	const IP       = 0x000010;
	const IP_RANGE = 0x000011;

	const HAVE_SIZE       = 0x000100;
	const HAVE_PRECISION  = 0x000200;
	const HAVE_SCALE      = 0x000400;
	const HAVE_TIMEZONE   = 0x000800;
	const CAN_BE_UNSIGNED = 0x001000;

	private $size      = null;
	private $precision = null;
	private $scale     = null;

	private $null     = true;
	private $timezone = false;
	private $unsigned = false;

	protected static $names = [
		self::SMALLINT => 'SMALLINT', self::INTEGER => 'INTEGER', self::BIGINT => 'BIGINT', self::NUMERIC => 'NUMERIC',

		self::REAL => 'FLOAT', self::DOUBLE => 'DOUBLE PRECISION',

		self::BOOLEAN => 'BOOLEAN',

		self::CHAR => 'CHARACTER', self::VARCHAR => 'CHARACTER VARYING', self::TEXT => 'TEXT',

		self::DATE => 'DATE', self::TIME => 'TIME', self::TIMESTAMP => 'TIMESTAMP', self::TIMESTAMPTZ => 'TIMESTAMP', self::INTERVAL => 'INTERVAL',

		self::BINARY => 'BINARY',

		self::IP => 'IP', self::IP_RANGE => 'IP_RANGE',

		self::HSTORE => 'HSTORE', self::UUID => 'UUID',

		self::JSON => 'JSON',
		self::JSONB	=> 'JSONB',
	];

	/**
	 * @return DataType
	 **/
	public static function create($id) {
		return new self($id);
	}

	public static function getAnyId() {
		return self::BOOLEAN;
	}

	public static function smallint() {
		return new self(self::SMALLINT);
	}

	public static function int() {
		return new self(self::INTEGER);
	}

	public static function bigint() {
		return new self(self::BIGINT);
	}

	public static function numeric() {
		return new self(self::NUMERIC);
	}

	public static function real() {
		return new self(self::REAL);
	}

	public static function double() {
		return new self(self::DOUBLE);
	}

	public static function bool() {
		return new self(self::BOOLEAN);
	}

	public static function char() {
		return new self(self::CHAR);
	}

	public static function varchar() {
		return new self(self::VARCHAR);
	}

	public static function text() {
		return new self(self::TEXT);
	}

	public static function date() {
		return new self(self::DATE);
	}

	public static function time() {
		return new self(self::TIME);
	}

	public static function timestamp() {
		return new self(self::TIMESTAMP);
	}

	public static function timestamptz() {
		return new self(self::TIMESTAMPTZ);
	}

	public static function interval() {
		return new self(self::INTERVAL);
	}

	public static function binary() {
		return new self(self::BINARY);
	}

	public static function ip() {
		return new self(self::IP);
	}

	public static function iprange() {
		return new self(self::IP_RANGE);
	}

	public static function hstore() {
		return new self(self::HSTORE);
	}

	public static function uuid() {
		return new self(self::UUID);
	}

	public static function json() {
		return new self(self::JSON);
	}

	public static function jsonb() {
		return new self(self::JSONB);
	}

	public function getSize() {
		return $this->size;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DataType
	 **/
	public function setSize($size) {
		Assert::isInteger($size);
		Assert::isTrue($this->hasSize());

		$this->size = $size;

		return $this;
	}

	public function hasSize() {
		return (bool)($this->id & self::HAVE_SIZE);
	}

	public function getPrecision() {
		return $this->precision;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DataType
	 **/
	public function setPrecision($precision) {
		Assert::isInteger($precision);
		Assert::isTrue(($this->id & self::HAVE_PRECISION) > 0);

		$this->precision = $precision;

		return $this;
	}

	public function hasPrecision() {
		return (bool)($this->id & self::HAVE_PRECISION);
	}

	public function getScale() {
		return $this->scale;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DataType
	 **/
	public function setScale($scale) {
		Assert::isInteger($scale);
		Assert::isTrue(($this->id & self::HAVE_SCALE) > 0);

		$this->scale = $scale;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DataType
	 **/
	public function setTimezoned($zoned = false) {
		Assert::isTrue(($this->id & self::HAVE_TIMEZONE) > 0);

		$this->timezone = (true === $zoned);

		return $this;
	}

	public function isTimezoned() {
		return $this->timezone;
	}

	/**
	 * @return DataType
	 **/
	public function setNull($isNull = false) {
		$this->null = ($isNull === true);

		return $this;
	}

	public function isNull() {
		return $this->null;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DataType
	 **/
	public function setUnsigned($unsigned = false) {
		Assert::isTrue(($this->id && self::CAN_BE_UNSIGNED) > 0);

		$this->unsigned = ($unsigned === true);

		return $this;
	}

	public function isUnsigned() {
		return $this->unsigned;
	}

	public function toDialectString(Dialect $dialect) {
		$out = $dialect->typeToString($this);

		if ($this->unsigned) {
			$out .= ' UNSIGNED';
		}

		if ($this->id & self::HAVE_PRECISION) {
			if ($this->precision) {

				switch ($this->id) {

					case self::TIME:
					case self::TIMESTAMP:

						$out .= "({$this->precision})";
						break;

					case self::NUMERIC:

						$out .= $this->precision ? "({$this->size}, {$this->precision})" : "({$this->size})";
						break;

					default:

						throw new WrongStateException();
				}
			}
		} elseif ($this->hasSize()) {
			if (!$this->size) {
				throw new WrongStateException("type '{$this->name}' must have size");
			}

			$out .= "({$this->size})";
		}

		if ($this->id & self::HAVE_TIMEZONE) {
			$out .= $dialect->timeZone($this->timezone);
		}

		$out .= $this->null ? ' NULL' : ' NOT NULL';

		return $out;
	}
}
