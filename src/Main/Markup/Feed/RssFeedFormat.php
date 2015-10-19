<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash, Dmitry E. Demidov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Singleton;

/**
 * @ingroup Feed
 **/
final class RssFeedFormat extends FeedFormat {

	const VERSION = '2.0';

	/**
	 * @return RssFeedFormat
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return RssChannelWorker
	 **/
	public function getChannelWorker() {
		return RssChannelWorker::me();
	}

	/**
	 * @return RssItemWorker
	 **/
	public function getItemWorker() {
		return RssItemWorker::me();
	}

	public function isAcceptable(\SimpleXMLElement $xmlFeed) {
		return (($xmlFeed->getName() == 'rss') && (isset($xmlFeed['version'])) && ($xmlFeed['version'] == self::VERSION));
	}
}
