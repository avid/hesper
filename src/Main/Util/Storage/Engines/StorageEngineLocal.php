<?php
/**
 * StorageEngineLocal
 * @author Aleksandr Babaev <babaev@adonweb.ru>
 * @date   2013.04.18
 */
namespace Hesper\Main\Util\Storage\Engines;

use Hesper\Core\Exception\WrongStateException;
use InvalidArgumentException;

class StorageEngineLocal extends StorageEngineStreamable {

	protected $canCopy = true;

	public function copy($from, $to = null) {
		return copy($this->get($from), $this->getPath($to), $this->context);
	}

	public function rename ($from, $to) {
		return rename($this->get($from), $this->getPath($to), $this->context);
	}


	public function get($file) {
		if( is_readable($file) ) {
			return $file;
		}
		return parent::get($file);
	}


	protected function parseConfig ($data) {
        if ( !isset($data['path']) ) {
            throw new InvalidArgumentException('Path must be configured');
        }

        if ( preg_match('/(\:\/\/)/iu',$data['path']) ) {
            throw new InvalidArgumentException('Path must not contain protocol: '.$data['path']);
        }

        if ( !is_dir($data['path']) || !is_readable($data['path']) ) {
            throw new InvalidArgumentException('Path must be readable directory: '.$data['path']);
        }

        $data['dsn'] = $data['path'];

        return parent::parseConfig($data);
    }
}