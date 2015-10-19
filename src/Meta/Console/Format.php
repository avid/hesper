<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Console;

use Hesper\Core\Base\StaticFactory;

final class Format extends StaticFactory {

	// dumb and straigh beautifier
	public static function indentize($data) {
		$out = null;

		$indent = 0;
		$chain = 1;
		$first = true; // for schema.php-like files

		foreach (explode("\n", $data) as $string) {
			$string = preg_replace('~^[\t]+~', null, rtrim($string)) . "\n";

			if ($string == "}\n") {
				$indent -= $chain;
				$chain = 1;
			} elseif ($string == ")->\n") {
				--$indent;
			} elseif ($string == ")\n") {
				--$indent;
			} elseif ($string == ");\n") {
				--$indent;
			} elseif ($string == "),\n") {
				--$indent;
			} elseif ($string == "?>\n") {
				$indent = 0;
			} elseif ($string[0] == '?') {
				++$indent;
			}

			if ($string <> "\n") {
				if ($indent > 0) {
					$out .= str_pad(null, $indent, "\t", STR_PAD_LEFT) . $string;
				} else {
					$out .= $string;
				}
			}

			if (substr($string, -2, 2) == "{\n") {
				++$indent;
			} elseif (substr_count($string, "'") == 2 && substr($string, -3, 3) == "=>\n") {
				++$indent;
				++$chain;
			} elseif ($string[0] == '$' && (substr($string, -2, 2) == "=\n" || substr($string, -3, 3) == "->\n")) {
				++$indent;
				++$chain;
			} elseif (substr($string, -2, 2) == "(\n") {
				++$indent;
			} elseif ($string == "\n" && $indent == 0) {
				++$indent;
			} elseif ($string == "return\n") {
				++$indent;
				++$chain;
			} elseif ($string == "\n" && $chain > 1) {
				$indent -= $chain - 1;
				$chain = 1;
			} elseif ($string[0] == ':') {
				--$indent;
			} elseif ($string == "),\n") {
				--$indent;
			}

			if ($string == "\n") {
				if (!$first && ($indent > 0)) {
					$out .= str_pad(null, $indent, "\t", STR_PAD_LEFT) . $string;
				} else {
					$out .= $string;
					$first = false;
				}
			}
		}

		return $out;
	}
}
