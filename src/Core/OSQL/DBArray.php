<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\DB\PostgresDialect;

/**
 * Container for passing binary values into OSQL queries.
 * @ingroup OSQL
 * @ingroup Module
 **/
class DBArray extends DBValue {

    protected $type = null;

    /**
     * @return DBArray
     */
    public static function create($value)
    {
        return new self($value);
    }

    public function integers() {
        $this->type = DataType::INTEGER;
        return $this;
    }

    public function floats() {
        $this->type = DataType::REAL;
        return $this;
    }

    public function strings() {
        $this->type = DataType::VARCHAR;
        return $this;
    }

    public function json() {
        $this->type = DataType::JSON;
        return $this;
    }

    public function jsonb() {
        $this->type = DataType::JSONB;
        return $this;
    }

    public function toDialectString(Dialect $dialect)
    {
        if ($this->type == DataType::JSON || $this->type == DataType::JSONB) {
            return $dialect->quoteJson($this->getValue(), $this->type);
        } else {
            return $dialect->quoteArray($this->getValue(), $this->type);
        }
    }
}