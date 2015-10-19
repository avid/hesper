<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexandr S. Krotov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Singleton;

/**
 * Class YandexRssFeedFormat
 * @see     http://partner.news.yandex.ru/tech.pdf
 * @package Hesper\Main\Markup\Feed
 */
final class YandexRssFeedFormat extends FeedFormat {

	const  YANDEX_NAMESPACE_URI    = 'http://news.yandex.ru';
	const  YANDEX_NAMESPACE_PREFIX = 'yandex';

	/**
	 * @return YandexRssFeedFormat
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
	 * @return YandexRssItemWorker
	 **/
	public function getItemWorker() {
		return YandexRssItemWorker::me();
	}

	public function isAcceptable(\SimpleXMLElement $xmlFeed) {
		return (($xmlFeed->getName() == 'rss') && (isset($xmlFeed['version'])) && ($xmlFeed['version'] == RssFeedFormat::VERSION) && array_key_exists(self::YANDEX_NAMESPACE_PREFIX, $xmlFeed->getDocNamespaces(true)));
	}
}
