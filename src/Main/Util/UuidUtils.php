<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;

class UuidUtils extends StaticFactory {

	public static function generate() {
		return
			sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
				mt_rand( 0, 0x0fff ) | 0x4000,
				mt_rand( 0, 0x3fff ) | 0x8000,
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );

	}

	public static function check( $uuid ) {
		return Assert::checkUUID( $uuid );
	}

	public static function assert( $uuid ) {
		Assert::isUUID( $uuid );
	}

	public static function parseHash( $hash ) {
		return preg_replace( '/([a-f0-9]{8})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{12})/iu', '$1-$2-$3-$4-$5', strtolower($hash) );
	}

}