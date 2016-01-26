<?php
/**
 * Class for StorageEngineImageShack
 * @author Aleksandr Babaev <babaev@adonweb.ru>
 * @date   2013.01.1/23/13
 */
namespace Hesper\Main\Util\Storage\Engines;

use Exception;

class StorageEngineImageShack extends StorageEngineHTTP
{
    protected $hasHttpLink = true;

    public function store($localFile, $desiredName) {

        $xml = simplexml_load_string(parent::store($localFile, $desiredName));

        if (isset($xml->error)) {
            throw new Exception('Couldn`t save file to ImageShack: ' . $xml->error);
        }

        return (string)$xml->links->image_link;
    }

}
