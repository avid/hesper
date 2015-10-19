<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Net\Http;

/**
 * Interface HttpResponse
 * @package Hesper\Main\Net\Http
 */
interface HttpResponse {

	/**
	 * @return HttpStatus
	 **/
	public function getStatus();

	public function getReasonPhrase();

	/**
	 * @return array of headers
	 **/
	public function getHeaders();

	public function hasHeader($name);

	public function getHeader($name);

	public function getBody();
}
