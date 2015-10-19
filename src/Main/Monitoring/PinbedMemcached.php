<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Main\Monitoring;

use Hesper\Core\Cache\SocketMemcached;

/**
 * Class PinbedMemcached
 * @package Hesper\Main\Monitoring
 */
final class PinbedMemcached extends SocketMemcached {

	/**
	 * @return PinbedMemcached
	 **/
	public static function create($host = SocketMemcached::DEFAULT_HOST, $port = SocketMemcached::DEFAULT_PORT, $buffer = SocketMemcached::DEFAULT_BUFFER) {
		return new self($host, $port, $buffer);
	}

	public function __construct($host = SocketMemcached::DEFAULT_HOST, $port = SocketMemcached::DEFAULT_PORT, $buffer = SocketMemcached::DEFAULT_BUFFER) {
		if (PinbaClient::isEnabled()) {
			PinbaClient::me()->timerStart('memcached_' . $host . '_' . $port . '_connect', ['memcached_connect' => $host . '_' . $port]);
		}

		parent::__construct($host, $port, $buffer);

		if (PinbaClient::isEnabled()) {
			PinbaClient::me()->timerStop('memcached_' . $host . '_' . $port . '_connect');
		}
	}
}
