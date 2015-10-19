<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Timestamp;

/**
 * Class FeedItem
 * @package Hesper\Main\Markup\Feed
 */
class FeedItem {

	private $id        = null;
	private $title     = null;
	private $content   = null;
	private $summary   = null;
	private $published = null;
	private $link      = null;
	private $category  = null;

	/**
	 * @return FeedItem
	 **/
	public static function create($title) {
		return new self($title);
	}

	public function __construct($title) {
		$this->title = $title;
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * @return FeedItem
	 **/
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return FeedItem
	 **/
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	/**
	 * @return FeedItem
	 **/
	public function setContent($content) {
		$this->content = $content;

		return $this;
	}

	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @return FeedItem
	 **/
	public function setSummary($summary) {
		$this->summary = $summary;

		return $this;
	}

	/**
	 * @return Timestamp
	 **/
	public function getPublished() {
		return $this->published;
	}

	/**
	 * @return FeedItem
	 **/
	public function setPublished(Timestamp $published) {
		$this->published = $published;

		return $this;
	}

	public function getLink() {
		return $this->link;
	}

	/**
	 * @return FeedItem
	 **/
	public function setLink($link) {
		$this->link = $link;

		return $this;
	}

	public function getCategory() {
		return $this->category;
	}

	/**
	 * @return FeedItem
	 **/
	public function setCategory($category) {
		$this->category = $category;

		return $this;
	}
}
