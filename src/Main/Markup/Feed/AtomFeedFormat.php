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
 * Class AtomFeedFormat
 * @package Hesper\Main\Markup\Feed
 */
final class AtomFeedFormat extends FeedFormat {

	/**
	 * @return AtomFeedFormat
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return AtomChannelWorker
	 **/
	public function getChannelWorker() {
		return AtomChannelWorker::me();
	}

	/**
	 * @return AtomItemWorker
	 **/
	public function getItemWorker() {
		return AtomItemWorker::me();
	}

	public function isAcceptable(\SimpleXMLElement $xmlFeed) {
		return ($xmlFeed->getName() == 'feed');
	}
}
