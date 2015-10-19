<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

/**
 * Class FeedChannel
 * @package Hesper\Main\Markup\Feed
 */
final class FeedChannel {

	private $title       = null;
	private $link        = null;
	private $description = null;
	private $feedItems   = [];

	/**
	 * @return FeedChannel
	 **/
	public static function create($title) {
		return new self($title);
	}

	public function __construct($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return FeedChannel
	 **/
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return FeedChannel
	 **/
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	public function getLink() {
		return $this->link;
	}

	/**
	 * @return FeedChannel
	 **/
	public function setLink($link) {
		$this->link = $link;

		return $this;
	}

	public function getFeedItems() {
		return $this->feedItems;
	}

	/**
	 * @return FeedChannel
	 **/
	public function setFeedItems($feedItems) {
		$this->feedItems = $feedItems;

		return $this;
	}

	/**
	 * @return FeedChannel
	 **/
	public function addFeedItem(FeedItem $feedItem) {
		$this->feedItems[] = $feedItem;

		return $this;
	}
}
