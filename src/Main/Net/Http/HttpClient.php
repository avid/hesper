<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Net\Http;

use Hesper\Main\Flow\HttpRequest;

/**
 * Interface HttpClient
 * @package Hesper\Main\Net\Http
 */
interface HttpClient {

	/**
	 * @param $timeout int in seconds
	 **/
	public function setTimeout($timeout);

	public function getTimeout();

	/**
	 * whether to follow header Location or not
	 **/
	public function setFollowLocation(/* boolean */ $really);

	public function isFollowLocation();

	/**
	 * maximum number of header Location followed
	 **/
	public function setMaxRedirects($maxRedirects);

	public function getMaxRedirects();

	/**
	 * @param $request HttpRequest
	 *
	 * @return HttpResponse
	 **/
	public function send(HttpRequest $request);

	/**
	 * @param $key   string
	 * @param $value string
	 *
	 * @return CurlHttpClient
	 **/
	public function setOption($key, $value);

	/**
	 * @param $key string
	 *
	 * @return CurlHttpClient
	 **/
	public function dropOption($key);

	public function getOption($key);

	/**
	 * @param $really boolean
	 *
	 * @return CurlHttpClient
	 **/
	public function setNoBody($really);

	public function hasNoBody();

	/**
	 * @param $maxFileSize int
	 *
	 * @return CurlHttpClient
	 **/
	public function setMaxFileSize($maxFileSize);

	public function getMaxFileSize();
}
