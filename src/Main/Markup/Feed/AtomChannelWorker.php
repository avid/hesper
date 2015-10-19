<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * Class AtomChannelWorker
 * @package Hesper\Main\Markup\Feed
 */
final class AtomChannelWorker extends Singleton implements FeedChannelWorker {

	/**
	 * @return AtomChannelWorker
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return FeedChannel
	 **/
	public function makeChannel(\SimpleXMLElement $xmlFeed) {
		$feedChannel = FeedChannel::create((string)$xmlFeed->title);

		if (isset($xmlFeed->link)) {
			if (is_array($xmlFeed->link)) {
				$feedChannel->setLink((string)$xmlFeed->link[0]);
			} else {
				$feedChannel->setLink((string)$xmlFeed->link);
			}
		}

		return $feedChannel;
	}

	public function toXml(FeedChannel $channel, $itemsXml) {
		throw new UnimplementedFeatureException('implement me!');
	}
}
