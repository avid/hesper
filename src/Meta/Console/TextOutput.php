<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Console;

class TextOutput {

	/**
	 * @return TextOutput
	 **/
	public function write($text) {
		echo $text;

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function writeLine($text) {
		echo $text . "\n";

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function writeErr($text) {
		fwrite(STDERR, $text);

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function writeErrLine($text) {
		fwrite(STDERR, $text . PHP_EOL);

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function newLine() {
		echo "\n";

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function setMode($attribute = ConsoleMode::ATTR_RESET_ALL, $foreground = ConsoleMode::FG_WHITE, $background = ConsoleMode::BG_BLACK) {
		// nop

		return $this;
	}

	/**
	 * @return TextOutput
	 **/
	public function resetAll() {
		// nop

		return $this;
	}
}
