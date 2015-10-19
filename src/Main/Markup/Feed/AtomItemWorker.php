<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Singleton;
use Hesper\Core\Base\Timestamp;
use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * @ingroup Feed
 **/
final class AtomItemWorker extends Singleton implements FeedItemWorker {

	/**
	 * @return AtomItemWorker
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function makeItems(\SimpleXMLElement $xmlFeed) {
		$result = [];

		foreach ($xmlFeed->entry as $entry) {
			$feedItem = FeedItem::create((string)$entry->title);

			if (isset($entry->content)) {
				$feedItem->setContent($this->makeFeedItemContent($entry->content));
			}

			if (isset($entry->summary)) {
				$feedItem->setSummary($this->makeFeedItemContent($entry->summary));
			}

			if (isset($entry->id)) {
				$feedItem->setId($entry->id);
			}

			$result[] = $feedItem->setPublished(Timestamp::create(strtotime((string)$entry->updated)))->setLink((string)$entry->link);
		}

		return $result;
	}

	public function toXml(FeedItem $item) {
		throw new UnimplementedFeatureException('implement me!');
	}

	private function makeFeedItemContent($content) {
		$feedItemContent = FeedItemContent::create();

		if (isset($content->attributes()->type)) {
			switch ((string)$content->attributes()->type) {

				case 'text':

					$feedItemContent->setType(new FeedItemContentType(FeedItemContentType::TEXT));

					break;

				case 'html':

					$feedItemContent->setType(new FeedItemContentType(FeedItemContentType::HTML));

					break;

				case 'xhtml':

					$feedItemContent->setType(new FeedItemContentType(FeedItemContentType::XHTML));

					break;
			}
		}

		return $feedItemContent->setBody((string)$content);
	}
}
