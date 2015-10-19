<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifier;
use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * SQLite dialect through PDO.
 * @package Hesper\Core\DB
 * @see     http://www.sqlite.org/
 */
class LitePDODialect extends LiteDialect {

	public function quoteValue($value) {
		/// @see Sequenceless for this convention

		if ($value instanceof Identifier && !$value->isFinalized()) {
			return 'null';
		}

		if (Assert::checkInteger($value)) {
			return $value;
		}

		return $this->getLink()
		            ->quote($value);
	}

	public function quoteBinary($data) {
		//here must be PDO::PARAM_LOB, but i couldn't get success result, so used base64_encode/decode
		return $this->getLink()
		            ->quote(base64_encode($data), \PDO::PARAM_STR);
	}

	public function unquoteBinary($data) {
		try {
			return base64_decode($data);
		} catch (\Exception $e) {
			throw new UnimplementedFeatureException('Wrong encoding, if you get it, throw correct exception');
		}
	}

	/**
	 * @return PDO
	 */
	protected function getLink() {
		return parent::getLink();
	}
}
