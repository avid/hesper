<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Garmonbozia Research Group
 */
namespace Hesper\Core\Base;

use Hesper\Core\DB\Dialect;
use Hesper\Core\OSQL\DialectString;

/**
 * Ideal Identifiable interface implementation. ;-)
 * @package Hesper\Core\Base
 * @see     Identifiable
 */
class IdentifiableObject implements Identifiable, DialectString {

	protected $id = null;

	/**
	 * @return IdentifiableObject
	 **/
	public static function wrap($id) {
		$io = new self;

		return $io->setId($id);
	}

	/**
	 * @return integer|string
	 */
	public function getId() {
		if ($this->id instanceof Identifier && $this->id->isFinalized()) {
			return $this->id->getId();
		} else {
			return $this->id;
		}
	}

	/**
	 * @return IdentifiableObject
	 **/
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		return $dialect->quoteValue($this->getId());
	}
}
