<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Crypto;

use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Class Crypter
 * @package Hesper\Main\Crypto
 */
final class Crypter {

	private $algorithm  = null;
	private $iv         = null;

	public static function create($algorithm, $vector) {
		return new self($algorithm, $vector);
	}

	private function  __construct($algorithm, $vector) {
		if( !in_array($algorithm, openssl_get_cipher_methods(true)) ) {
			throw new UnsupportedMethodException('Algorithm is not supported');
		}
		$this->algorithm = $algorithm;

		$this->iv = $this->createIV($vector);
	}

	public function  __destruct() {
		$this->algorithm = null;
		$this->iv = null;
	}

	public function encrypt($secret, $data) {
		return openssl_encrypt($data, $this->algorithm, $secret, 0, $this->iv);
	}

	public function decrypt($secret, $encryptedData) {
		return openssl_decrypt($encryptedData, $this->algorithm, $secret, 0, $this->iv);
	}

	private function createIV($vector) {
		return substr(openssl_digest($vector, 'whirlpool', true), 0, openssl_cipher_iv_length($this->algorithm));
	}
}
