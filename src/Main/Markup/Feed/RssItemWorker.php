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

/**
 * @ingroup Feed
**/
final class RssItemWorker extends Singleton implements FeedItemWorker {

	/**
	 * @return RssItemWorker
	**/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function makeItems(\SimpleXMLElement $xmlFeed) {
		$result = array();

		if (isset($xmlFeed->channel->item)) {
			foreach ($xmlFeed->channel->item as $item) {
				$feedItem =
					FeedItem::create((string) $item->title)->
					setContent(
						FeedItemContent::create()->
						setBody((string) $item->description)
					)->
					setPublished(
						Timestamp::create(
							strtotime((string) $item->pubDate)
						)
					)->
					setLink((string) $item->link);

				if (isset($item->guid))
					$feedItem->setId($item->guid);

				if (isset($item->category))
					$feedItem->setCategory((string) $item->category);

				$result[] = $feedItem;
			}
		}

		return $result;
	}

	public function toXml(FeedItem $item) {
		return
			'<item>'
				.(
					$item->getPublished()
						?
							'<pubDate>'
								.date('r', $item->getPublished()->toStamp())
							.'</pubDate>'
						: null
				)
				.(
					$item->getId()
						?
							'<guid isPermaLink="false">'
								.$item->getId()
							.'</guid>'
						: null
				)
				.'<title>'.$item->getTitle().'</title>'
				.(
					$item->getLink()
						?
							'<link>'
							.str_replace("&", "&amp;", $item->getLink())
							.'</link>'
						: null
				)
				.(
					$item->getSummary()
						? '<description>'.$item->getSummary().'</description>'
						: null
				)
				.(
					$item->getCategory()
						? '<category>'.$item->getCategory().'</category>'
						: null
				)
			.'</item>';
	}
}
