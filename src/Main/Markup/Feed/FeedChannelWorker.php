<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Markup\Feed;

/**
 * @ingroup Feed
 **/
interface FeedChannelWorker {

	public function makeChannel(\SimpleXMLElement $xmlFeed);

	public function toXml(FeedChannel $channel, $itemsXml);
}
