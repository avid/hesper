<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Instantiatable;

/**
 * Interface FeedItemWorker
 * @package Hesper\Main\Markup\Feed
 */
interface FeedItemWorker extends Instantiatable {

	public function makeItems(\SimpleXMLElement $xmlFeed);

	public function toXml(FeedItem $item);
}
