<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash
 */
namespace Hesper\Main\Markup\Feed;

/**
 * Class FeedItemContent
 * @package Hesper\Main\Markup\Feed
 */
final class FeedItemContent {

	private $type = null;
	private $body = null;

	/**
	 * @return FeedItemContent
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return FeedItemContentType
	 **/
	public function getType() {
		return $this->type;
	}

	/**
	 * @return FeedItemContent
	 **/
	public function setType(FeedItemContentType $type) {
		$this->type = $type;

		return $this;
	}

	public function getBody() {
		return $this->body;
	}

	/**
	 * @return FeedItemContent
	 **/
	public function setBody($body) {
		$this->body = $body;

		return $this;
	}
}
