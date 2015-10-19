<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Aliased;
use Hesper\Core\DB\Dialect;

/**
 * Connected to concrete table DBField.
 * @ingroup OSQL
 * @ingroup Module
 **/
final class SelectField extends FieldTable implements Aliased {

	private $alias = null;

	/**
	 * @return SelectField
	 **/
	public static function create(DialectString $field, $alias) {
		return new self($field, $alias);
	}

	public function __construct(DialectString $field, $alias) {
		parent::__construct($field);
		$this->alias = $alias;
	}

	public function getAlias() {
		return $this->alias;
	}

	public function getName() {
		if ($this->field instanceof DBField) {
			return $this->field->getField();
		}

		return $this->alias;
	}

	public function toDialectString(Dialect $dialect) {
		return parent::toDialectString($dialect) . ($this->alias ? ' AS ' . $dialect->quoteField($this->alias) : null);
	}
}
