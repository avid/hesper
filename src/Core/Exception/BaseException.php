<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Exception;

class BaseException extends \Exception {

	public function __toString() {
		return "[$this->message] in: \n" . $this->getTraceAsString();
	}
}
