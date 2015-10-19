<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Net\Mail;

/**
 * Interface MailBuilder
 * @package Hesper\Main\Net\Mail
 */
interface MailBuilder {

	/// returns encoded body as string
	public function getEncodedBody();

	/// returns all related headers as string
	public function getHeaders();
}
