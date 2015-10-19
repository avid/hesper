<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Console;

/**
 * @ingroup MetaBase
 **/
final class ColoredTextOutput extends TextOutput {

	/**
	 * @return ColoredTextOutput
	 **/
	public function setMode($attribute = ConsoleMode::ATTR_RESET_ALL, $foreground = ConsoleMode::FG_WHITE, $background = ConsoleMode::BG_BLACK) {
		echo chr(0x1B) . '[' . $attribute . ';' . $foreground . ';' . $background . 'm';

		return $this;
	}

	/**
	 * @return ColoredTextOutput
	 **/
	public function resetAll() {
		echo chr(0x1B) . '[0m';

		return $this;
	}

	/**
	 * @return string colored text
	 **/
	public function wrapString($text, $attribute = ConsoleMode::ATTR_RESET_ALL, $foreground = ConsoleMode::FG_WHITE, $background = ConsoleMode::BG_BLACK) {
		return chr(0x1B) . '[' . $attribute . ';' . $foreground . ';' . $background . 'm' . $text . chr(0x1B) . '[0m';
	}
}