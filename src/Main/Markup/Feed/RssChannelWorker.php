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
 * @ingroup Feed
**/
final class RssChannelWorker extends Singleton implements FeedChannelWorker {

	/**
	 * @return RssChannelWorker
	**/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return FeedChannel
	**/
	public function makeChannel(\SimpleXMLElement $xmlFeed) {
		if (
			(!isset($xmlFeed->channel))
			|| (!isset($xmlFeed->channel->title))
		)
			throw new WrongStateException(
				'there are no channels in given rss'
			);

		$feedChannel =
			FeedChannel::create((string) $xmlFeed->channel->title);

		if (isset($xmlFeed->channel->link))
			$feedChannel->setLink((string) $xmlFeed->channel->link);

		return $feedChannel;
	}

	public function toXml(FeedChannel $channel, $itemsXml) {
		return
			'<rss version="'.RssFeedFormat::VERSION.'">'
				.'<channel>'
					.'<title>'.$channel->getTitle().'</title>'
					.(
						$channel->getLink()
							? '<link>'.$channel->getLink().'</link>'
							: null
					)
					.(
						$channel->getDescription()
							?
								'<description>'
								.$channel->getDescription()
								.'</description>'
							: null
					)
					.$itemsXml
				.'</channel>'
			.'</rss>';
	}
}
