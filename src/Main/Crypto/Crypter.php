<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Crypto;

use Hesper\Core\Exception\WrongStateException;

/**
 * Class Crypter
 * @package Hesper\Main\Crypto
 */
final class Crypter {

	private $crResource = null;
	private $keySize    = null;
	private $iv         = null;

	public static function create($algorithm, $mode) {
		return new self($algorithm, $mode);
	}

	public function  __construct($algorithm, $mode) {
		if (!$this->crResource = mcrypt_module_open($algorithm, null, $mode, null)) {
			throw new WrongStateException('Mcrypt Module did not open.');
		}

		$this->iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->crResource), MCRYPT_DEV_URANDOM);

		$this->keySize = mcrypt_enc_get_key_size($this->crResource);
	}

	public function  __destruct() {
		mcrypt_module_close($this->crResource);
	}

	public function encrypt($secret, $data) {
		mcrypt_generic_init($this->crResource, $this->createKey($secret), $this->iv);

		return mcrypt_generic($this->crResource, $data);
	}

	public function decrypt($secret, $encryptedData) {
		mcrypt_generic_init($this->crResource, $this->createKey($secret), $this->iv);

		// crop padding garbage
		return rtrim(mdecrypt_generic($this->crResource, $encryptedData), "\0");
	}

	private function createKey($secret) {
		return substr(md5($secret), 0, $this->keySize);
	}
}
