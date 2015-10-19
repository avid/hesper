<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexander A. Zaytsev
 */
namespace Hesper\Main\UI\View;

use Hesper\Main\Flow\Model;

/**
 * Class EmptyGifView
 * @package Hesper\Main\UI\View
 */
final class EmptyGifView implements View {
	/**
	 * @return EmptyGifView
	**/
	public static function create() {
		return new self;
	}

	/**
	 * @return EmptyGifView
	**/
	public function render(Model $model = null) {
		header('Content-Type: image/gif');
		header('Content-Length: 43');
		header('Accept-Ranges: none');

		// NOTE: this is hardcoded empty gif 1x1 image
		print
			"GIF89\x61\x01\x00\x01\x00\x80\x00\x00\xff\xff\xff\x00"
			."\x00\x00\x21\xf9\x04\x01\x00\x00\x00\x00\x2c\x00\x00\x00"
			."\x00\x01\x00\x01\x00\x00\x02\x02\x44\x01\x00\x3b";

		return $this;
	}
}
