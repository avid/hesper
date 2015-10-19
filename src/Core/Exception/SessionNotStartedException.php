<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Exception;

final class SessionNotStartedException extends BaseException {

	public function __construct() {
		return parent::__construct('start session before assign or access session variables');
	}

}