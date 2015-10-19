<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\WrongStateException;

/**
 * Class FeedFormat
 * @package Hesper\Main\Markup\Feed
 */
abstract class FeedFormat extends Singleton {

	abstract public function getChannelWorker();

	abstract public function getItemWorker();

	abstract public function isAcceptable(\SimpleXMLElement $xmlFeed);

	public function parse(\SimpleXMLElement $xmlFeed) {
		$this->checkWorkers();

		return $this->getChannelWorker()->makeChannel($xmlFeed)->setFeedItems($this->getItemWorker()->makeItems($xmlFeed));
	}

	public function toXml(FeedChannel $channel) {
		$this->checkWorkers();

		$itemsXml = null;
		$itemWorker = $this->getItemWorker();

		foreach ($channel->getFeedItems() as $feedItem) {
			$itemsXml .= $itemWorker->toXml($feedItem);
		}

		return $this->getChannelWorker()->toXml($channel, $itemsXml);
	}

	private function checkWorkers() {
		if (!$this->getChannelWorker()) {
			throw new WrongStateException('Setup channelWorker must be assigned');
		}

		if (!$this->getItemWorker()) {
			throw new WrongStateException('Setup itemWorker must be assigned');
		}

		return $this;
	}
}
