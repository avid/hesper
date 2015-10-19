<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexandr S. Krotov
 */
namespace Hesper\Main\Markup\Feed;

/**
 * Class YandexRssFeedItem
 * @package Hesper\Main\Markup\Feed
 */
final class YandexRssFeedItem extends FeedItem {

	private $fullText = null;

	/**
	 * @return YandexRssFeedItem
	 **/
	public static function create($title) {
		return new self($title);
	}

	public function getFullText() {
		return $this->fullText;
	}

	/**
	 * @return YandexRssFeedItem
	 **/
	public function setFullText($fullText) {
		$this->fullText = $fullText;

		return $this;
	}
}
