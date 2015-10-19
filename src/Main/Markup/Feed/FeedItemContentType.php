<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash
 */
namespace Hesper\Main\Markup\Feed;

use Hesper\Core\Base\Enum;

/**
 * Class FeedItemContentType
 * @package Hesper\Main\Markup\Feed
 */
final class FeedItemContentType extends Enum {

	const TEXT  = 1;
	const HTML  = 2;
	const XHTML = 3;

	protected static $names = [
		self::TEXT => 'text',
		self::HTML => 'html',
		self::XHTML => 'xhtml'
	];
}
