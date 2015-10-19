<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Pismenny, Dmitry A. Lomash
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Class FeedReader
 * @package Hesper\Main\Markup\Feed
 */
final class FeedReader {

	private $xml     = null;
	private $formats = [];

	/**
	 * @return FeedReader
	 **/
	public static function create() {
		return new self;
	}

	public function __construct() {
		$this->formats[] = YandexRssFeedFormat::me();
		$this->formats[] = AtomFeedFormat::me();
		$this->formats[] = RssFeedFormat::me();
	}

	/**
	 * @return \SimpleXMLElement
	 **/
	public function getXml() {
		return $this->xml;
	}

	/**
	 * @return FeedChannel
	 **/
	public function parseFile($file) {
		try {
			$this->xml = simplexml_load_file($file);
		} catch (BaseException $e) {
			throw new WrongArgumentException('Invalid link or content: ' . $e->getMessage());
		}

		if (!$this->xml) {
			throw new WrongStateException('simplexml_load_file failed.');
		}

		return $this->parse();
	}

	/**
	 * @return FeedReader
	 **/
	public function parseXml($xml) {
		$this->xml = new \SimpleXMLElement($xml);

		return $this->parse();
	}

	/**
	 * @return FeedChannel
	 **/
	private function parse() {
		foreach ($this->formats as $format) {
			if ($format->isAcceptable($this->xml)) {
				return $format->parse($this->xml);
			}
		}

		throw new WrongStateException('you\'re using unsupported format of feed');
	}
}
