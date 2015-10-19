<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\Form;

use Hesper\Core\Exception\BaseException;

class CustomFormException extends BaseException {

	/** @var string */
	private $field = null;

	public static function create($field, $message = "", $code = 0, \Exception $previous = null) {
		$exception = new self($message, $code, $previous);
		$exception->setField($field);
		return $exception;
	}

	/**
	 * @return string
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * @param string $field
	 */
	public function setField($field) {
		$this->field = $field;
	}

}