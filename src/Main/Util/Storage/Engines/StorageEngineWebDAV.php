<?php
/**
 * @project ActionPay
 * @author Alex Gorbylev <alex@adonweb.ru>
 */
 
/**
 * Class StorageEngineWebDav
 */
namespace Hesper\Main\Util\Storage\Engines;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Main\Flow\HttpRequest;
use Hesper\Main\Net\Http\CurlHttpClient;
use Hesper\Main\Net\Http\HttpMethod;
use Hesper\Main\Net\HttpUrl;

class StorageEngineWebDAV extends StorageEngineHTTP {

	protected $hasHttpLink = true;
	protected $canReadRemote = false;
	protected $ownNamingPolicy = true;

	protected $folderShardingDepth = 3;

	public function store ($file, $desiredName) {
		$sendRequest = HttpRequest::create()
			->setMethod(HttpMethod::put())
			->setUrl( HttpUrl::create()->parse($this->getUploadLink($desiredName)) );

		/** @var CurlHttpResponse $resp */
		$curl =
			CurlHttpClient::create()
				->setOption(CURLOPT_PUT, true)
				->setOption(CURLOPT_INFILE, fopen($file, 'r'))
				->setOption(CURLOPT_INFILESIZE, filesize($file))
				->setOption(CURLOPT_TIMEOUT, 25);
		if ( is_array($this->uploadOptions) && isset($this->uploadOptions['userpwd']) ) {
			$curl
				->setOption(CURLOPT_HTTPAUTH, CURLAUTH_ANY)
				->setOption(CURLOPT_USERPWD, $this->uploadOptions['userpwd']);
		}

        $upload = function() use ($curl, $sendRequest) {
            $resp = $curl->send($sendRequest);
            $status = $resp->getStatus()->getId();

            if ( $status<200 || $status>=400 ) {
                throw new MissingElementException("Got HTTP response code {$status}");
            }
        };

        $this->tryToDo($upload, "File ({$desiredName}) was not stored, reason: %s");

		return $desiredName;
	}

	protected function getUploadLink($file) {
		if ( !empty($this->uploadUrl) ) {
			return $this->uploadUrl.$this->generateSubPath($file).$file;
		}

		throw new UnsupportedMethodException('Don`t know how to return http link');
	}

    protected function unlink($file) {
		$sendRequest = HttpRequest::create()
            ->setMethod(HttpMethod::delete())
            ->setUrl( HttpUrl::create()->parse($this->getUploadLink($file)) );

        /** @var CurlHttpResponse $resp */
        $curl =
            CurlHttpClient::create()
                ->setOption(CURLOPT_CUSTOMREQUEST, "DELETE")
                ->setOption(CURLOPT_TIMEOUT, 25);
        if ( is_array($this->uploadOptions) && isset($this->uploadOptions['userpwd']) ) {
            $curl
                ->setOption(CURLOPT_HTTPAUTH, CURLAUTH_ANY)
                ->setOption(CURLOPT_USERPWD, $this->uploadOptions['userpwd']);
        }

        $delete = function() use ($curl, $sendRequest) {
            $response = $curl->send($sendRequest);
            $status = $response->getStatus()->getId();

            if ($status<200 || $status>=400) {
                throw new MissingElementException("Got HTTP response code {$status}");
            }
        };

        $this->tryToDo($delete, "File ({$file}) was not deleted, reason: %s");

        return true;
    }


}