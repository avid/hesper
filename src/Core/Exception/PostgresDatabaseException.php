<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladlen Y. Koshelev
 */
namespace Hesper\Core\Exception;

class PostgresDatabaseException extends DatabaseException {

	public function __construct($message = null, $code = null)
	{
		parent::__construct($message);
		$this->code = $code;
	}

}
